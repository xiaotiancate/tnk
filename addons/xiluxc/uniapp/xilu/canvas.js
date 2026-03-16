/**
 *
 * @param {CanvasContext} ctx canvas上下文
 * @param {string} text       文本的内容
 * @param {number} fontsize   文本的字体大小
 * @param {number} lineheight 文本的字体行高
 * @param {number} x          文本左上角 x坐标
 * @param {number} y          文本左上角 y坐标
 * @param {number} width      文本的显示宽度
 * @param {number} line       文本的显示行数（超出会省略）
 * @param {Boolean}center     文本是否居中显示
 * @param {Boolean}color     文本是否居中显示
 */
function fillTextCut (ctx,text,fontsize,lineheight,x,y,width,line,center,color) {
  var chr = text.split('');//这个方法是将一个字符串分割成字符串数组
  var temp = "";
  var row = [];
  ctx.setFontSize(fontsize);
  ctx.setFillStyle(color)
  ctx.setTextBaseline('top'); // 文字顶部对齐，便于计算文字到顶部距离
  for (var a = 0; a < chr.length; a++) {
    if (ctx.measureText(temp).width < width) {
      temp += chr[a];
    }
    else {
      a--; //这里添加了a-- 是为了防止字符丢失
      row.push(temp);
      temp = '';
    }
  }
  row.push(temp);
  if (row.length > line) {
    var rowCut = row.slice(0, line);
    var rowPart = rowCut[line-1];
    var test = '';
    var empty = [];
    for (var a = 0; a < rowPart.length; a++) {
      if (ctx.measureText(test).width < (width - fontsize)) {
        test += rowPart[a];
      }
      else {
        break;
      }
    }
    empty.push(test);
    var group = empty[0] + "..."//超出的用...表示
    rowCut.splice(line-1, 1, group);
    row = rowCut;
  }
  for (var b = 0; b < row.length; b++) {
    if (center) x = x + (width - ctx.measureText(row[b]).width) * 0.5;
    ctx.fillText(row[b], x, y + b * lineheight, width);
  }
  ctx.save();
}
/**
 *
 * @param {CanvasContext} ctx canvas上下文
 * @param {number} x 圆角矩形选区的左上角 x坐标
 * @param {number} y 圆角矩形选区的左上角 y坐标
 * @param {number} r 圆角的半径
 * @param {string} c 绘制的颜色
 */
function clipCircleRect (ctx, x, y, r, c='#fff') {
  var d =2 * r;
  var cx = x + r;
  var cy = y + r;
  ctx.save()
  ctx.fillRect(0,0,0,0) // 临时性解决多次clip出现bug问题，https://developers.weixin.qq.com/community/develop/doc/000e463db405e0d0f6c61a13356000
  ctx.beginPath() //开始绘制
  ctx.arc(cx, cy, r, 0, 2 * Math.PI,false)
  ctx.closePath()
  ctx.setFillStyle(c)
  ctx.fill()
  ctx.restore()
}
/**
 *
 * @param {CanvasContext} ctx canvas上下文
 * @param {number} x   圆角矩形选区的左上角 x坐标
 * @param {number} y   圆角矩形选区的左上角 y坐标
 * @param {number} r   圆角的半径
 * @param {string} img 绘制的图片地址
 */
function clipCircleImg (ctx, x, y, r, img) {
  var d =2 * r;
  var cx = x + r;
  var cy = y + r;
  ctx.save()
  ctx.fillRect(0,0,0,0) // 临时性解决多次clip出现bug问题，https://developers.weixin.qq.com/community/develop/doc/000e463db405e0d0f6c61a13356000
  ctx.beginPath() //开始绘制
  ctx.arc(cx, cy, r, 0, 2 * Math.PI,false)
  ctx.closePath()
  ctx.clip()
  ctx.drawImage(img, x, y, d, d)
  ctx.restore()
}
/**
 *
 * @param {CanvasContext} ctx canvas上下文
 * @param {number} x 圆角矩形选区的左上角 x坐标
 * @param {number} y 圆角矩形选区的左上角 y坐标
 * @param {number} w 圆角矩形选区的宽度
 * @param {number} h 圆角矩形选区的高度
 * @param {number} r 圆角的半径
 * @param {string} c 绘制的颜色
 */
function clipRoundRect (ctx, x, y, w, h, r, c='#fff') {
  ctx.save()
  ctx.fillRect(0,0,0,0) // 临时性解决多次clip出现bug问题，https://developers.weixin.qq.com/community/develop/doc/000e463db405e0d0f6c61a13356000
  // 开始绘制
  ctx.beginPath()
  // 左上角
  ctx.arc(x + r, y + r, r, Math.PI, Math.PI * 1.5)
  // border-top
  ctx.moveTo(x + r, y)
  ctx.lineTo(x + w - r, y)
  ctx.lineTo(x + w, y + r)
  // 右上角
  ctx.arc(x + w - r, y + r, r, Math.PI * 1.5, Math.PI * 2)
  // border-right
  ctx.lineTo(x + w, y + h - r)
  ctx.lineTo(x + w - r, y + h)
  // 右下角
  ctx.arc(x + w - r, y + h - r, r, 0, Math.PI * 0.5)
  // border-bottom
  ctx.lineTo(x + r, y + h)
  ctx.lineTo(x, y + h - r)
  // 左下角
  ctx.arc(x + r, y + h - r, r, Math.PI * 0.5, Math.PI)
  // border-left
  ctx.lineTo(x, y + r)
  ctx.lineTo(x + r, y)

  // 因为边缘描边存在锯齿，最好指定使用 transparent 填充
  // 这里是使用 fill 还是 stroke都可以，二选一即可
  // ctx.setStrokeStyle('transparent')
  ctx.setFillStyle(c)
  // 这里是使用 fill 还是 stroke都可以，二选一即可，但是需要与上面对应
  ctx.fill()
  // ctx.stroke()
  ctx.closePath()
  // 剪切
  ctx.clip()
  ctx.restore()
}
/**
 *
 * @param {CanvasContext} ctx canvas上下文
 * @param {number} x   圆角矩形选区的左上角 x坐标
 * @param {number} y   圆角矩形选区的左上角 y坐标
 * @param {number} w   圆角矩形选区的宽度
 * @param {number} h   圆角矩形选区的高度
 * @param {number} r   圆角的半径
 * @param {string} img 绘制的图片地址
 */
function clipRoundImg (ctx, x, y, w, h, r, img) {
  ctx.save()
  ctx.fillRect(0,0,0,0) // 临时性解决多次clip出现bug问题，https://developers.weixin.qq.com/community/develop/doc/000e463db405e0d0f6c61a13356000
  // 开始绘制
  ctx.beginPath()
  // 左上角
  ctx.arc(x + r, y + r, r, Math.PI, Math.PI * 1.5)
  // border-top
  ctx.moveTo(x + r, y)
  ctx.lineTo(x + w - r, y)
  ctx.lineTo(x + w, y + r)
  // 右上角
  ctx.arc(x + w - r, y + r, r, Math.PI * 1.5, Math.PI * 2)
  // border-right
  ctx.lineTo(x + w, y + h - r)
  ctx.lineTo(x + w - r, y + h)
  // 右下角
  ctx.arc(x + w - r, y + h - r, r, 0, Math.PI * 0.5)
  // border-bottom
  ctx.lineTo(x + r, y + h)
  ctx.lineTo(x, y + h - r)
  // 左下角
  ctx.arc(x + r, y + h - r, r, Math.PI * 0.5, Math.PI)
  // border-left
  ctx.lineTo(x, y + r)
  ctx.lineTo(x + r, y)

  ctx.closePath()
  // 剪切
  ctx.clip()
  ctx.drawImage(img, x, y, w, h)
  ctx.restore()
}

module.exports = {
  fillTextCut: fillTextCut,
  clipCircleRect: clipCircleRect,
  clipCircleImg: clipCircleImg,
  clipRoundRect: clipRoundRect,
  clipRoundImg: clipRoundImg
}
