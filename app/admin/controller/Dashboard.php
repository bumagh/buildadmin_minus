<?php

namespace app\admin\controller;

use app\admin\model\AdminGroup;
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
        $subCounts = AdminGroupAccess::query()->where("group_id",">",intval($curGid))->select()->count();
       $this->success('', [
            'counts' => AdminGroupAccess::count(),
            'subCounts' => $subCounts
        ]);
    }

    public function dashboard()
    {
        $this->success('', [
            'remark' => get_route_remark()
        ]);
    }
}