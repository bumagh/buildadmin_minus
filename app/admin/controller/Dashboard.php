<?php

namespace app\admin\controller;

use app\admin\model\AdminGroup;
use app\admin\model\Admin;
use app\common\controller\Backend;
use app\admin\model\AdminGroupAccess;
class Dashboard extends Backend
{
    public function initialize(): void
    {
        parent::initialize();
    }

    public function getAgentCount(){
        $curUid = $this->auth->id;
        $curGid = AdminGroupAccess::query()->field("group_id")->where("uid",$curUid)->select()->toArray()[0]['group_id'];
        $subCounts = AdminGroupAccess::query()->where("group_id",">",$curGid)->select()->count();
        //账号金币数
        $coin = Admin::query()->where("id",$curUid)->field(["coin","id"])->column("coin");
       $this->success('', [
            'counts' => AdminGroupAccess::count(),
            'subCounts' => $subCounts,
            'coin'=>$coin,
            "adminInfo"=>Admin::query()->where("id",$curUid)->select()->toArray()[0]
        ]);
    }

    public function dashboard()
    {
        $this->success('', [
            'remark' => get_route_remark()
        ]);
    }
}