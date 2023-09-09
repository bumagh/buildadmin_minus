<?php

namespace app\admin\model\agent;

use think\Model;

/**
 * CoinLog
 */
class CoinLog extends Model
{
    // 表名
    protected $name = 'agent_coin_log';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;


    public function admin(): \think\model\relation\BelongsTo
    {
        return $this->belongsTo(\app\admin\model\Admin::class, 'admin_id', 'id');
    }
}