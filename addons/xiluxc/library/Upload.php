<?php
namespace addons\xiluxc\library;


class Upload{

    /**二维码图片上传
     * @param $filePath
     * @param $fileData
     * @return bool
     */
    public function uploadApi($tempFile) {
        // 这里的 File 类必须使用我们自定义的 \addons\mydemo\library\File 类，不能使用 \think\File 类
        $file = new \addons\xiluxc\library\File($tempFile);
        $file->setUploadInfo(['name' => basename($tempFile), 'type' => $file->getMime(), 'tmp_name' => $tempFile, 'error' => 0, 'size' => $file->getSize()]);
        $file->isTest(true);

        request()->file(['file' => $file]);

        $storage = config('upload.storage');
        if ($storage === 'local') {
            // 本地上传
            $upload = new \app\common\library\Upload($file);
            $attachment = $upload->upload();
            $qrcode2 = cdnurl($attachment->url, true);
        } else {
            // 云存储上传
            request()->param('isApi', true);
            try {
                \think\App::invokeMethod(["\\addons\\{$storage}\\controller\\Index", "upload"], ['isApi' => true]);
                // 这里无法获取上传返回的数据，请在下方的 HttpResponseException 中处理
            } catch (\think\exception\HttpResponseException $e) {
                $ret = $e->getResponse()->getData();
                $qrcode2 = $ret['data']['fullurl'];
            }
        }
        return $qrcode2 ?? '';
    }

}