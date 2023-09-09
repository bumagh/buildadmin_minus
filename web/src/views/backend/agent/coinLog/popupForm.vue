<template>
    <!-- 对话框表单 -->
    <!-- 建议使用 Prettier 格式化代码 -->
    <!-- el-form 内可以混用 el-form-item、FormItem、ba-input 等输入组件 -->
    <el-dialog
        class="ba-operate-dialog"
        :close-on-click-modal="false"
        :model-value="['Add', 'Edit'].includes(baTable.form.operate!)"
        @close="baTable.toggleForm"
        width="50%"
    >
        <template #header>
            <div class="title" v-drag="['.ba-operate-dialog', '.el-dialog__header']" v-zoom="'.ba-operate-dialog'">
                {{ baTable.form.operate ? t(baTable.form.operate) : '' }}
            </div>
        </template>
        <el-scrollbar v-loading="baTable.form.loading" class="ba-table-form-scrollbar">
            <div
                class="ba-operate-form"
                :class="'ba-' + baTable.form.operate + '-form'"
                :style="'width: calc(100% - ' + baTable.form.labelWidth! / 2 + 'px)'"
            >
                <el-form
                    v-if="!baTable.form.loading"
                    ref="formRef"
                    @submit.prevent=""
                    @keyup.enter="baTable.onSubmit(formRef)"
                    :model="baTable.form.items"
                    label-position="right"
                    :label-width="baTable.form.labelWidth + 'px'"
                    :rules="rules"
                >
                    <FormItem
                        type="remoteSelect"
                        prop="admin_id"
                        label="选择代理商"
                        v-model="baTable.form.items!.admin_id"
                        :placeholder="t('Click select')"
                        :input-attr="{
                            pk: 'admin.id',
                            field: 'nickname',
                            'remote-url': '/admin/auth.Admin/index',
                            onChange: getAdd,
                        }"
                    />
                    <FormItem
                        :label="t('agent.coinLog.before')"
                        type="number"
                        prop="before"
                        :input-attr="{ step: 1, disabled: true }"
                        v-model.number="baTable.form.items!.before"
                        :placeholder="t('Please input field', { field: t('agent.coinLog.before') })"
                    />
                    <FormItem
                        :label="t('agent.coinLog.coin')"
                        type="number"
                        prop="coin"
                        :input-attr="{ min: 1, step: 100, onInput: changeCoin }"
                        v-model.number="baTable.form.items!.coin"
                        :placeholder="t('Please input field', { field: t('agent.coinLog.coin') })"
                    />
                    <FormItem
                        :label="t('agent.coinLog.after')"
                        type="number"
                        prop="after"
                        :input-attr="{ step: 1, disabled: true }"
                        v-model.number="baTable.form.items!.after"
                        :placeholder="t('Please input field', { field: t('agent.coinLog.after') })"
                    />
                    <FormItem
                        :label="t('agent.coinLog.remark')"
                        type="textarea"
                        v-model="baTable.form.items!.remark"
                        prop="remark"
                        :input-attr="{ rows: 3 }"
                        @keyup.enter.stop=""
                        @keyup.ctrl.enter="baTable.onSubmit(formRef)"
                        :placeholder="t('Please input field', { field: t('agent.coinLog.remark') })"
                    />
                </el-form>
            </div>
        </el-scrollbar>
        <template #footer>
            <div :style="'width: calc(100% - ' + baTable.form.labelWidth! / 1.8 + 'px)'">
                <el-button @click="baTable.toggleForm()">{{ t('Cancel') }}</el-button>
                <el-button v-blur :loading="baTable.form.submitLoading" @click="baTable.onSubmit(formRef)" type="primary">
                    {{ baTable.form.operateIds && baTable.form.operateIds.length > 1 ? t('Save and edit next item') : t('Save') }}
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script setup lang="ts">
import { reactive, ref, inject, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import type baTableClass from '/@/utils/baTable'
import FormItem from '/@/components/formItem/index.vue'
import type { FormInstance, FormItemRule } from 'element-plus'
import { buildValidatorData } from '/@/utils/validate'

import { add } from '/@/api/backend/agent/coinLog'
import { AdminInfo } from '/@/stores/interface'
import { useAdminInfo } from '/@/stores/adminInfo'

const formRef = ref<FormInstance>()
const baTable = inject('baTable') as baTableClass

const { t } = useI18n()
const state: {
    agentInfo: AdminInfo
    after: number
} = reactive({
    agentInfo: useAdminInfo(),
    after: 0,
})
const getAdd = () => {
    if (!baTable.form.items!.admin_id || parseInt(baTable.form.items!.admin_id) <= 0) {
        return
    }
    add(baTable.form.items!.admin_id).then((res) => {
        state.agentInfo = res.data.agent
        state.after = res.data.agent.coin
        baTable.form.items!.before = state.agentInfo.coin
        state.after = baTable.form.items!.coin + baTable.form.items!.before
        baTable.form.items!.after = state.after
    })
}
// 打开表单时刷新用户数据
watch(
    () => baTable.form.operate,
    (newValue) => {
        if (newValue) {
            getAdd()
        }
    }
)

const changeCoin = (value: string) => {
    if (!state.agentInfo || typeof state.agentInfo == 'undefined') {
        state.after = 0
        return
    }
    baTable.form.items!.coin = parseInt(value)
    state.after = state.agentInfo.coin + parseInt(value)
    baTable.form.items!.after = state.after
}

const rules: Partial<Record<string, FormItemRule[]>> = reactive({
    admin_id: [buildValidatorData({ name: 'required', message: t('Please select field') })],
    create_time: [buildValidatorData({ name: 'date', title: t('agent.coinLog.create_time') })],
})
</script>

<style scoped lang="scss"></style>
