/**
 * 通用图片转Base64（支持本地/网络路径）
 * @param {string} path 图片路径（支持http/https/本地路径）
 * @returns {Promise<string>} Base64字符串
 */
export function pathToBase64(path) {
    return new Promise((resolve, reject) => {
        // 0. 如果是网络图片（http/https开头）
        if (/^https?:\/\//i.test(path)) {
            handleNetworkImage(path, resolve, reject);
            return;
        }

        // 1. 浏览器环境
        if (typeof window === 'object' && 'document' in window) {
            handleBrowserImage(path, resolve, reject);
            return;
        }

        // 2. 5+环境（HBuilder/UniApp）
        if (typeof plus === 'object') {
            handlePlusImage(path, resolve, reject);
            return;
        }

        // 3. 微信小程序
        if (typeof wx === 'object' && wx.canIUse('getFileSystemManager')) {
            handleWxImage(path, resolve, reject);
            return;
        }

        reject(new Error('Unsupported environment'));
    });
}

// ------------------- 各环境处理器 -------------------
function handleNetworkImage(url, resolve, reject) {
    // 浏览器环境优先
    if (typeof window === 'object' && 'document' in window) {
        const img = new Image();
        img.crossOrigin = 'Anonymous'; // 关键点：解决跨域问题
        
        img.onload = function() {
            try {
                const canvas = document.createElement('canvas');
                canvas.width = img.width;
                canvas.height = img.height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);
                resolve(canvas.toDataURL('image/jpeg', 0.8));
                canvas.width = canvas.height = 0;
            } catch (e) {
                reject(e);
            }
        };
        
        img.onerror = function() {
            reject(new Error('Image load error'));
        };
        
        // 添加时间戳避免缓存
        img.src = url + (url.includes('?') ? '&' : '?') + 't=' + Date.now();
    } 
    // 微信小程序环境
    else if (typeof wx === 'object') {
        wx.downloadFile({
            url: url,
            success: function(res) {
                wx.getFileSystemManager().readFile({
                    filePath: res.tempFilePath,
                    encoding: 'base64',
                    success: function(res) {
                        resolve(`data:image/jpeg;base64,${res.data}`);
                    },
                    fail: function(err) {
                        reject(err);
                    }
                });
            },
            fail: function(err) {
                reject(err);
            }
        });
    }
    // 其他环境
    else {
        reject(new Error('Network images not supported in this environment'));
    }
}

function handleBrowserImage(path, resolve, reject) {
    // 尝试XMLHttpRequest方案
    if (typeof FileReader === 'function') {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', path, true);
        xhr.responseType = 'blob';
        
        xhr.onload = function() {
            if (this.status === 200) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    resolve(e.target.result);
                };
                reader.onerror = function() {
                    reject(new Error('FileReader error'));
                };
                reader.readAsDataURL(this.response);
            } else {
                reject(new Error(`HTTP ${this.status}`));
            }
        };
        
        xhr.onerror = function() {
            reject(new Error('XHR error'));
        };
        
        xhr.send();
    } 
    // 备选Canvas方案
    else {
        const img = new Image();
        img.onload = function() {
            try {
                const canvas = document.createElement('canvas');
                canvas.width = img.width;
                canvas.height = img.height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);
                resolve(canvas.toDataURL());
                canvas.width = canvas.height = 0;
            } catch (e) {
                reject(e);
            }
        };
        img.onerror = function() {
            reject(new Error('Image load error'));
        };
        img.src = path;
    }
}

function handlePlusImage(path, resolve, reject) {
    try {
        const localPath = getLocalFilePath(path);
        plus.io.resolveLocalFileSystemURL(localPath, function(entry) {
            entry.file(function(file) {
                const reader = new plus.io.FileReader();
                reader.onload = function(data) {
                    resolve(data.target.result);
                };
                reader.onerror = function(err) {
                    reject(err);
                };
                reader.readAsDataURL(file);
            }, function(err) {
                reject(err);
            });
        }, function(err) {
            reject(err);
        });
    } catch (e) {
        reject(e);
    }
}

function handleWxImage(path, resolve, reject) {
    wx.getFileSystemManager().readFile({
        filePath: path,
        encoding: 'base64',
        success: function(res) {
            resolve(`data:image/jpeg;base64,${res.data}`);
        },
        fail: function(err) {
            reject(err);
        }
    });
}

// ------------------- 辅助函数 -------------------
function getLocalFilePath(path) {
    // 已识别的前缀直接返回
    const prefixes = ['_www', '_doc', '_documents', '_downloads', 'file://', '/storage/emulated/0/'];
    for (const prefix of prefixes) {
        if (path.startsWith(prefix)) {
            return path;
        }
    }
    // 处理绝对路径
    return path.startsWith('/') ? `_www${path}` : `_www/${path}`;
}