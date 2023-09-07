<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use app\admin\model\AdminGroupAccess;
class Dashboard extends Backend
{
    public function initialize(): void
    {
        parent::initialize();
    }

    public function getAgentCount(){
       $this->success('', [
            'counts' => AdminGroupAccess::count()
        ]);
    }
    public function dashboard()
    {
        $this->success('', [
            'remark' => get_route_remark()
        ]);
    }
}