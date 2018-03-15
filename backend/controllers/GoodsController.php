<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use function React\Promise\all;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

class GoodsController extends \yii\web\Controller
{
    //解决防跨站攻击
    public $enableCsrfValidation = false;

    //商品列表
    public function actionIndex()
    {
        //实例化request组件
        $request = \Yii::$app->request;
        $get = $request->get();
        if (!empty($get['SearchForm']['name']) || !empty($get['SearchForm']['sn']) || !empty($get['SearchForm']['minPrice']) || !empty($get['SearchForm']['minPrice'])) {
//            echo 11;exit;
            $condition = [];
            if (!empty($get['SearchForm']['name'])) {
                $condition['name'] = ['name' => $get['SearchForm']['name']];

            }
            if (!empty($get['SearchForm']['sn'])) {
                $condition['sn'] = ['like', 'sn', $get['SearchForm']['sn']];

            }
            if (!empty($get['SearchForm']['minPrice'])) {
                $condition['shop_price'] = ['>', 'shop_price', $get['SearchForm']['minPrice']];
//                var_dump($condition);exit;=>
//                $condition = $condition['shop_price'];
        }
            if (!empty($get['SearchForm']['maxPrice'])) {
                $condition['maxPrice'] = ['<', 'shop_price', $get['SearchForm']['maxPrice']];
            }
            $page = new Pagination();
            $page->totalCount = Goods::find()->count();
            $page->defaultPageSize = 10;
            $goods = Goods::find()->where(!empty($get['SearchForm']['sn']) ? $condition['sn'] : '')->andWhere(!empty($get['SearchForm']['minPrice']) ? $condition['shop_price'] : '')->andWhere(!empty($get['SearchForm']['name']) ? $condition['name'] : '')->andWhere(!empty($get['SearchForm']['maxPrice']) ? $condition['maxPrice'] : '')->andWhere(['status'=>1])->offset($page->offset)->limit($page->limit)->all();
//            var_dump($goods);exit;
//            var_dump($goods);exit;
            return $this->render('index', ['page' => $page, 'goods' => $goods]);
        }

        //实例化分页工具
        $page = new Pagination();
        $page->totalCount = Goods::find()->count();
        $page->defaultPageSize = 10;
        $goods = Goods::find()->offset($page->offset)->limit($page->limit)->where(['status'=>1])->all();
        return $this->render('index', ['page' => $page, 'goods' => $goods]);
    }

    //添加商品
    public function actionAdd()
    {
        //实例化request组件
        $request = \Yii::$app->request;
        //实例化表单模型
        $goods = new Goods();
        $goods_intro = new GoodsIntro();
        $model = new GoodsCategory();
        $goods_day_count = new GoodsDayCount();
        $nodes = GoodsCategory::find()->select(['id', 'parent_id', 'name'])->asArray()->all();
        $nodes[] = ['id' => 0, 'parent_id' => 0, 'name' => '顶级分类'];
        //判断提交方式接收数据
        if ($request->isPost) {
            $goods->load($request->post());
            $goods_intro->load($request->post());
            //判断是否提交成功
            if ($goods->validate() && $goods_intro->validate()) {
                $day = date('Ymd', time());
                $count = GoodsDayCount::findOne(['day' => $day]);
                if ($count == null) {
                    $goods_day_count->day = $day;
                    $goods_day_count->count = 0;
                    $goods_day_count->save();
                    $count = GoodsDayCount::findOne(['day' => $day]);
                }
                $num = $count->count + 1;
                $goods->sn = $day . str_pad($num, 5, 0, STR_PAD_LEFT);
                $count->count = $num;
                $count->save();
                $goods->create_time = time();
                $goods->save();
                $goods_intro->goods_id = $goods->id;
                $goods_intro->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success', '添加成功');
                //跳转页面
                return $this->redirect(['goods/index']);
            }
        }
        //加载视图
        return $this->render('add', ['goods' => $goods, 'goods_intro' => $goods_intro, 'model' => $model, 'nodes' => json_encode($nodes)]);

    }

    //修改商品
    public function actionEdit($id)
    {
        //实例化request组件
        $request = \Yii::$app->request;
        //通过id查询数据回显
        $goods = Goods::findOne(['id' => $id]);
        $goods_intro = GoodsIntro::findOne(['goods_id' => $id]);
        $model = GoodsCategory::findOne(['id' => $goods->goods_category_id]);
        $nodes = GoodsCategory::find()->select(['id', 'parent_id', 'name'])->asArray()->all();
        $nodes[] = ['id' => 0, 'parent_id' => 0, 'name' => '顶级分类'];

        //通过接收数据方式完成修改保存
        if ($request->isPost) {
            $goods->load($request->post());
            $goods_intro->load($request->post());
            //判断是否提交成功
            if ($goods->validate() && $goods_intro->validate()) {
                $goods->save();
                $goods_intro->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success', '修改成功');
                //跳转页面
                return $this->redirect(['goods/index']);
            }
        }
        //加载视图
        return $this->render('add', ['goods' => $goods, 'goods_intro' => $goods_intro, 'model' => $model, 'nodes' => json_encode($nodes)]);
    }
    //删除商品
    public function actionDelete(){
        //实例化request组件
        $request = \Yii::$app->request;
        $id = $request->post('id');
        //实例化表单
        $good = Goods::findOne(['id'=>$id]);
        $good->status = 0;
        $res = $good->save();
        if ($res){
            return json_encode([
                'status'=>0,

            ]);
        }else{
            return json_encode([
                'status'=>1,

            ]);
        }
    }
    //回收站
    public function actionRecycle(){


        //实例化request组件
        $request = \Yii::$app->request;
        $get = $request->get();
        if (!empty($get['SearchForm']['name']) || !empty($get['SearchForm']['sn']) || !empty($get['SearchForm']['minPrice']) || !empty($get['SearchForm']['minPrice'])) {
//            echo 11;exit;
            $condition = [];
            if (!empty($get['SearchForm']['name'])) {
                $condition['name'] = ['name' => $get['SearchForm']['name']];

            }
            if (!empty($get['SearchForm']['sn'])) {
                $condition['sn'] = ['like', 'sn', $get['SearchForm']['sn']];

            }
            if (!empty($get['SearchForm']['minPrice'])) {
                $condition['shop_price'] = ['>', 'shop_price', $get['SearchForm']['minPrice']];
//                var_dump($condition);exit;=>
//                $condition = $condition['shop_price'];
            }
            if (!empty($get['SearchForm']['maxPrice'])) {
                $condition['maxPrice'] = ['<', 'shop_price', $get['SearchForm']['maxPrice']];
            }
            $page = new Pagination();
            $page->totalCount = Goods::find()->count();
            $page->defaultPageSize = 10;
            $goods = Goods::find()->where(!empty($get['SearchForm']['sn']) ? $condition['sn'] : '')->andWhere(!empty($get['SearchForm']['minPrice']) ? $condition['shop_price'] : '')->andWhere(!empty($get['SearchForm']['name']) ? $condition['name'] : '')->andWhere(!empty($get['SearchForm']['maxPrice']) ? $condition['maxPrice'] : '')->andWhere(['status'=>0])->offset($page->offset)->limit($page->limit)->all();
//            var_dump($goods);exit;
//            var_dump($goods);exit;
            return $this->render('recycle', ['page' => $page, 'goods' => $goods]);
        }

        //实例化分页工具
        $page = new Pagination();
        $page->totalCount = Goods::find()->count();
        $page->defaultPageSize = 10;
        $goods = Goods::find()->offset($page->offset)->limit($page->limit)->where(['status'=>0])->all();
        return $this->render('recycle', ['page' => $page, 'goods' => $goods]);
    }

    //恢复回收站数据
    public function actionRecovery(){

        //实例化request组件
        $request = \Yii::$app->request;
        $id = $request->post('id');
        //实例化表单
//        var_dump($id);exit;
        $good = Goods::findOne(['id'=>$id]);
        $good->status = 1;
        $res = $good->save();
        if ($res){
            return json_encode([
                'status'=>0,

            ]);
        }else{
            return json_encode([
                'status'=>1,

            ]);
        }
    }
    //预览
    public function actionPreview($id){
        //实例化数据表
        $goods = Goods::findOne(['id'=>$id]);
        $photos = GoodsGallery::find()->where(['goods_id'=>$id])->all();
//        var_dump($photos);exit;
        $contents = GoodsIntro::findOne(['goods_id'=>$id]);
        //加载视图
        return $this->render('preview',['goods'=>$goods,'photos'=>$photos,'contents'=>$contents]);
    }


    //处理上传文件
    public function actionLogo()
    {
        //实例化上传文件类
        $uploadFile = UploadedFile::getInstanceByName('file');
        $file = 'upload/goods/' . date('Y-m-d');

        if (!is_dir($file)) {
//            var_dump($file);exit;
            mkdir($file, 0777, true);
        }
        $file = $file . '/' . uniqid() . '.' . $uploadFile->extension;
        $result = $uploadFile->saveAs(\Yii::getAlias('@webroot') . '/' . $file, 0);
        if ($result) {
            //文件保存成功 返回文件路径
            $accessKey = "TxMyeDQ095vC5DtBNrUmE_PqD-Ds6I1mz3i__KJk";
            $secretKey = "M69Cr5LgZCbmJrdmxqRfDl2qvdPYiZB8nZ6P9F7g";
            $bucket = "yii2shop";
            // 构建鉴权对象
            $auth = new Auth($accessKey, $secretKey);
            // 生成上传 Token
            $token = $auth->uploadToken($bucket);
            // 要上传文件的本地路径
            $filePath = \Yii::getAlias('@webroot') . '/' . $file;
            // 上传到七牛后保存的文件名
            $key = '/' . $file;
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
//        echo "\n====> putFile result: \n";
            if ($err == null) {
                return json_encode([
                    'url' => "http://p4w1z299s.bkt.clouddn.com/{$key}"
                ]);
            } else {
                var_dump($err);
            }
//            return json_encode([
//                'url'=>$file
//            ]);
        }


    }

    //文本编译器
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix" => "http://admin.yuez.top",//图片访问路径前缀
                    "imagePathFormat" => "/upload/goods/{yyyy}{mm}{dd}/{time}{rand:6}", //上传保存路径
                    "imageRoot" => \Yii::getAlias("@webroot"),
                ],
            ]
        ];
    }

    //相册
    public function actionPhoto($id){
//        public $enableCsrfValidation = false;


        //实例化表单模型
        $models = new GoodsGallery();
//        var_dump($models);exit;
        $photos = GoodsGallery::find()->where(['goods_id'=>$id])->all();

        //加载试图
        return $this->render('photo',['models'=>$models,'id'=>$id,'photos'=>$photos]);
    }
    //接收相册的ajax请求保存到数据库
    public function actionPsave(){
        //实例化reques组件
        $request = \Yii::$app->request;

        //实例化表单模型
        $model = new GoodsGallery();
        if ($request->isPost){
            $model->goods_id = $request->post('goods_id');
            $model->path = $request->post('path');

                $model->save();
        }
    }
    public function actionPhotoDelete($id,$goods_id){
        //实例化表单模型

        $model = GoodsGallery::findOne(['id'=>$id]);
        //删除
        $model->delete();
        //提示信息
        \Yii::$app->session->setFlash('success','删除成功');
        //跳转页面
        return $this->redirect(['goods/photo','id'=>$goods_id]);
    }

    //过滤器
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::class,
                //默认情况对所有操作生效
                'except'=>['preview','logo','upload','psave','recovery']
            ],

        ];
    }

}
