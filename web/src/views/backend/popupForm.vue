<template>
    <!-- 对话框表单 -->
    <el-dialog class="ba-operate-dialog" :model-value="showDialog">
        <template #header>
            <div class="title" v-drag="['.ba-operate-dialog', '.el-dialog__header']" v-zoom="'.ba-operate-dialog'">充值页面</div>
        </template>
        <!-- <el-input v-model="coin" type="number" min="1" max="1000000" placeholder="输入充值金额" /> -->
        <FormItem
            type="remoteSelect"
            label="选择充值账号"
            v-model="formdata.agentId"
            :placeholder="t('Click select')"
            :input-attr="{
                pk: 'admin.id',
                field: 'username',
                'remote-url': '/admin/auth.Admin/index',
                onChange: handleChangeAgentId,
            }"
        />
        <FormItem
            label="充值金额"
            v-model="formdata.money"
            :input-attr="{ onChange: handleChangeMoney, min: 1, step: 1 }"
            type="number"
            placeholder="输入充值金额"
        />
        <FormItem label="选择支付方式" type="select" v-model="formdata.payType" :data="{ content: { GooglePay: 'GooglePay', Alipay: 'Alipay' } }" />
        <FormItem label="可获得金币数" v-model="formdata.coin" :input-attr="{ disabled: true }" type="number" />

        <template #footer>
            <div>
                <el-button @click="handleCancel">{{ t('Cancel') }}</el-button>
                <el-button v-blur @click="handleSubmit" type="primary"> 支付 </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import FormItem from '/@/components/formItem/index.vue'
import { ref } from 'vue'
import { payCheck } from '/@/api/backend/module'
import { useAdminInfo } from '/@/stores/adminInfo';
interface FormData {
    coin: number
    payType: string
    money: number
    agentId:number
}
const formdata = ref<FormData>({
    coin: 100000,
    payType: 'GooglePay',
    money: 1,
    agentId:useAdminInfo().id
})
interface Props {
    showDialog: boolean
}
const props = withDefaults(defineProps<Props>(), {
    showDialog: false,
})

const { t } = useI18n()
const handleCancel = () => {}
const handleSubmit = () => {
    //todo
    //pay();
    console.log(formdata.value)
}
const handleChangeMoney = () => {
    //按1美元10 0000
    formdata.value.coin = formdata.value.money * 100000
}
const handleChangeAgentId = ()=>{

}
</script>

<style scoped lang="scss">
.avatar-uploader {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    border-radius: var(--el-border-radius-small);
    box-shadow: var(--el-box-shadow-light);
    border: 1px dashed var(--el-border-color);
    cursor: pointer;
    overflow: hidden;
    width: 110px;
    height: 110px;
}
.avatar-uploader:hover {
    border-color: var(--el-color-primary);
}
.avatar {
    width: 110px;
    height: 110px;
    display: block;
}
.image-slot {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
}
</style>
