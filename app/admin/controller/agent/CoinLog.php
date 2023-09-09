<?php

namespace app\admin\controller\agent;

use app\admin\model\Admin;
use Throwable;
use app\common\controller\Backend;
use PhpZip\Util\MathUtil;

/**
 * agent_coin_log
 */
class CoinLog extends Backend
{
    /**
     * CoinLog模型对象
     * @var object
     * @phpstan-var \app\admin\model\agent\CoinLog
     */
    protected object $model;

    protected array|string $preExcludeFields = ['id', 'create_time'];

    protected array $withJoinTable = ['admin'];

    protected string|array $quickSearchField = ['id'];

    public function initialize(): void
    {
        parent::initialize();
        $this->model = new \app\admin\model\agent\CoinLog;
    }

    /**
     * 查看
     * @throws Throwable
     */
    public function index(): void
    {
        // 如果是 select 则转发到 select 方法，若未重写该方法，其实还是继续执行 index
        if ($this->request->param('select')) {
            $this->select();
        }

        /**
         * 1. withJoin 不可使用 alias 方法设置表别名，别名将自动使用关联模型名称（小写下划线命名规则）
         * 2. 以下的别名设置了主表别名，同时便于拼接查询参数等
         * 3. paginate 数据集可使用链式操作 each(function($item, $key) {}) 遍历处理
         */
        list($where, $alias, $limit, $order) = $this->queryBuilder();
        $res = $this->model
            ->withJoin($this->withJoinTable, $this->withJoinType)
            ->alias($alias)
            ->where($where)
            ->order($order)
            ->paginate($limit);
        $res->visible(['admin' => ['username']]);

        $this->success('', [
            'list'   => $res->items(),
            'total'  => $res->total(),
            'remark' => get_route_remark(),
        ]);
    }

    /**
     * 若需重写查看、编辑、删除等方法，请复制 @see \app\admin\library\traits\Backend 中对应的方法至此进行重写
     */
     /**
     * 添加
     * @param int $agentId
     * @throws Throwable
     */
    public function add(int $agentId = 0): void
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            Admin::where('id',  $data['admin_id'])->update(["coin"=>$data['after']]);
            parent::add();
        }

        $agent = Admin::where('id',intval($agentId))->find();
        if (!$agent) {
            $this->error(__("The agent can't find it"));
        }
        $this->success('', [
            'agent' => $agent
        ]);
    }
}