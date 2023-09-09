<template>
    <div class="default-main ba-table-box">
        <el-alert class="ba-table-alert" v-if="baTable.table.remark" :title="baTable.table.remark" type="info" show-icon />

        <!-- 表格顶部菜单 -->
        <!-- 自定义按钮请使用插槽，甚至公共搜索也可以使用具名插槽渲染，参见文档 -->
        <TableHeader
            :buttons="['refresh', 'add', 'edit', 'delete', 'comSearch', 'quickSearch', 'columnDisplay']"
            :quick-search-placeholder="t('Quick search placeholder', { fields: t('agent.coinLog.quick Search Fields') })"
        ></TableHeader>

        <!-- 表格 -->
        <!-- 表格列有多种自定义渲染方式，比如自定义组件、具名插槽等，参见文档 -->
        <!-- 要使用 el-table 组件原有的属性，直接加在 Table 标签上即可 -->
        <Table ref="tableRef"></Table>

        <!-- 表单 -->
        <PopupForm />
    </div>
</template>

<script setup lang="ts">
import { ref, provide, watch, reactive, onMounted } from 'vue'
import baTableClass from '/@/utils/baTable'
import { defaultOptButtons } from '/@/components/table'
import { baTableApi } from '/@/api/common'
import { useI18n } from 'vue-i18n'
import PopupForm from './popupForm.vue'
import Table from '/@/components/table/index.vue'
import TableHeader from '/@/components/table/header/index.vue'

import { add } from '/@/api/backend/agent/coinLog'

import { url } from '/@/api/backend/agent/coinLog'
import { AdminInfo } from '/@/stores/interface'
defineOptions({
    name: 'agent/coinLog',
})

const { t } = useI18n()
const tableRef = ref()
const optButtons: OptButton[] = defaultOptButtons(['edit', 'delete'])
const state: {
    agentInfo: AdminInfo
} = reactive({
    agentInfo: {} as AdminInfo,
})
/**
 * baTable 内包含了表格的所有数据且数据具备响应性，然后通过 provide 注入给了后代组件
 */
const baTable = new baTableClass(
    new baTableApi(url),
    {
        column: [
            { type: 'selection', align: 'center', operator: false },
            { label: t('agent.coinLog.id'), prop: 'id', align: 'center', width: 70, operator: 'RANGE', sortable: 'custom' },
            {
                label: t('agent.coinLog.admin__username'),
                prop: 'admin.username',
                align: 'center',
                operatorPlaceholder: t('Fuzzy query'),
                render: 'tags',
                operator: 'LIKE',
            },
            { label: t('agent.coinLog.coin'), prop: 'coin', align: 'center', operator: 'eq', sortable: 'custom' },
            { label: t('agent.coinLog.before'), prop: 'before', align: 'center', operator: 'eq', sortable: 'custom' },
            { label: t('agent.coinLog.after'), prop: 'after', align: 'center', operator: 'eq', sortable: 'custom' },
            {
                label: t('agent.coinLog.create_time'),
                prop: 'create_time',
                align: 'center',
                render: 'datetime',
                operator: 'RANGE',
                sortable: 'custom',
                width: 160,
                timeFormat: 'yyyy-mm-dd hh:MM:ss',
            },
            { label: t('agent.coinLog.remark'), prop: 'remark', align: 'center', operator: false },
            { label: t('Operate'), align: 'center', width: 100, render: 'buttons', buttons: optButtons, operator: false },
        ],
        dblClickNotEditColumn: [undefined],
    },
    {
        defaultItems: { coin: 1, before: 0, after: 0 },
    },{
        onSubmit: ({ formEl, operate, items }) => {
        },
    },
    {
   
    }
)

provide('baTable', baTable)

const getAgentInfo = (admin_id: number) => {
    if (admin_id && admin_id > 0) {
        add(admin_id).then((res) => {
            state.agentInfo = res.data.agent
        })
    } else {
        state.agentInfo = {} as AdminInfo
    }
}

getAgentInfo(baTable.comSearch.form.admin_id)

onMounted(() => {
    baTable.table.ref = tableRef.value
    baTable.mount()
    baTable.getIndex()?.then(() => {
        baTable.initSort()
        baTable.dragSort()
    })
})
watch(
    () => baTable.comSearch.form.admin_id,
    (newVal) => {
        baTable.form.defaultItems!.admin_id = newVal
        getAgentInfo(newVal)
    }
)
</script>

<style scoped lang="scss"></style>
