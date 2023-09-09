<template>
    <div class="default-main">
        <div class="banner">
            <el-row :gutter="10">
                <el-col :md="24" :lg="18">
                    <div class="welcome suspension">
                        <img class="welcome-img" :src="headerSvg" alt="" />
                        <div class="welcome-text">
                            <div class="welcome-title">{{ adminInfo.nickname + t('utils.comma') + getGreet() }}</div>
                        </div>
                    </div>
                </el-col>
            </el-row>
        </div>
        <div class="small-panel-box">
            <el-row :gutter="20">
                <el-col v-if="adminInfo.super == true" :sm="12" :lg="6">
                    <div class="small-panel users suspension">
                        <div class="small-panel-title">{{ t('dashboard.Total number of members') }}</div>
                        <div class="small-panel-content">
                            <div class="content-left">
                                <Icon color="#74A8B5" size="20" name="fa fa-users" />
                                <span id="users_number">{{ state.counts }}</span>
                            </div>
                        </div>
                    </div>
                </el-col>
                <el-col :sm="12" :lg="6">
                    <div class="small-panel users suspension">
                        <div class="small-panel-title">{{ t('dashboard.Total number of submembers') }}</div>
                        <div class="small-panel-content">
                            <div class="content-left">
                                <Icon color="#74A8B5" size="20" name="fa fa-users" />
                                <span id="users_sub_number">{{ state.subCounts }}</span>
                            </div>
                        </div>
                    </div>
                </el-col>

                <el-col :sm="12" :lg="6">
                    <div class="small-panel users suspension">
                        <div class="small-panel-title">{{ t('dashboard.Account coin') }}</div>
                        <div class="small-panel-content">
                            <div class="content-left">
                                <Icon color="#74A8B5" size="20" name="el-icon-Coin" />
                                <span id="admin_coin">{{ adminInfo.coin }}</span>
                            </div>
                            <el-button class="content-right" type="primary" @click="handlePay" plain>充值</el-button>
                        </div>
                    </div>
                </el-col>
            </el-row>
        </div>
    </div>
    <PopupForm v-model=state.showDialog />
</template>

<script setup lang="ts">
import { onMounted, onUnmounted, reactive, nextTick, onActivated, watch, onBeforeMount } from 'vue'
import headerSvg from '/@/assets/dashboard/header-1.svg'
import { CountUp } from 'countup.js'
import { useNavTabs } from '/@/stores/navTabs'
import { getAgentCount } from '/@/api/backend/dashboard'
import { useI18n } from 'vue-i18n'
import { useAdminInfo } from '/@/stores/adminInfo'
import { getGreet } from '/@/utils/common'
import { useEventListener } from '@vueuse/core'

import PopupForm from './popupForm.vue'

let workTimer: number

defineOptions({
    name: 'dashboard',
})

const { t } = useI18n()
const navTabs = useNavTabs()
const adminInfo = useAdminInfo()

const state: {
    charts: any[]
    counts: string
    subCounts: string
    workingTimeFormat: string
    pauseWork: boolean
    showDialog: boolean
} = reactive({
    charts: [],
    counts: '0',
    subCounts: '0',
    workingTimeFormat: '',
    pauseWork: false,
    showDialog: false,
})

const countUpFun = (id: string) => {
    nextTick(() => {
        let value = document.getElementById(id)?.innerText
        if (value) {
            new CountUp(id, parseInt(value), {
                startVal: 0,
                duration: 3,
            }).start()
        }
    })
}
const handlePay = () => {
    state.showDialog = true
}
const initCountUp = () => {
    countUpFun('users_number')
    countUpFun('users_sub_number')
    countUpFun('admin_coin')
}

const echartsResize = () => {
    nextTick(() => {
        for (const key in state.charts) {
            state.charts[key].resize()
        }
    })
}

onActivated(() => {
    echartsResize()
})

onMounted(() => {
    getAgentCount().then((res) => {
        state.counts = res.data.counts
        state.subCounts = res.data.subCounts
        initCountUp()
    })

    useEventListener(window, 'resize', echartsResize)
})

onBeforeMount(() => {
    for (const key in state.charts) {
        state.charts[key].dispose()
    }
})

onUnmounted(() => {
    clearInterval(workTimer)
})

watch(
    () => navTabs.state.tabFullScreen,
    () => {
        echartsResize()
    }
)
</script>

<style scoped lang="scss">
.welcome {
    background: #e1eaf9;
    border-radius: 6px;
    display: flex;
    align-items: center;
    padding: 15px 20px !important;
    box-shadow: 0 0 30px 0 rgba(82, 63, 105, 0.05);
    .welcome-img {
        height: 100px;
        margin-right: 10px;
        user-select: none;
    }
    .welcome-title {
        font-size: 1.5rem;
        line-height: 30px;
        color: var(--ba-color-primary-light);
    }
}

.small-panel-box {
    margin-top: 20px;
}
.small-panel {
    background-color: #e9edf2;
    border-radius: var(--el-border-radius-base);
    padding: 25px;
    margin-bottom: 20px;
    .small-panel-title {
        color: #92969a;
        font-size: 15px;
    }
    .small-panel-content {
        display: flex;
        align-items: flex-end;
        margin-top: 20px;
        color: #2c3f5d;
        .content-left {
            font-size: 24px;
            .icon {
                margin-right: 10px;
            }
            span {
                display: inline-block;
                font-size: 28px;
            }
        }
        .content-right {
            font-size: 18px;
            margin-left: auto;
        }
        .color-success {
            color: var(--el-color-success);
        }
        .color-warning {
            color: var(--el-color-warning);
        }
        .color-danger {
            color: var(--el-color-danger);
        }
        .color-info {
            color: var(--el-text-color-secondary);
        }
    }
}
.growth-chart {
    margin-bottom: 20px;
}
.user-growth-chart,
.file-growth-chart {
    height: 260px;
}
.new-user-growth {
    height: 300px;
}

.user-source-chart,
.user-surname-chart {
    height: 400px;
}
.new-user-item {
    display: flex;
    align-items: center;
    padding: 20px;
    margin: 10px 15px;
    box-shadow: 0 0 30px 0 rgba(82, 63, 105, 0.05);
    background-color: var(--ba-bg-color-overlay);
    .new-user-avatar {
        height: 48px;
        width: 48px;
        border-radius: 50%;
    }
    .new-user-base {
        margin-left: 10px;
        color: #2c3f5d;
        .new-user-name {
            font-size: 15px;
        }
        .new-user-time {
            font-size: 13px;
        }
    }
    .new-user-arrow {
        margin-left: auto;
    }
}
.new-user-card :deep(.el-card__body) {
    padding: 0;
}

@media screen and (max-width: 425px) {
    .welcome-img {
        display: none;
    }
}
@media screen and (max-width: 1200px) {
    .lg-mb-20 {
        margin-bottom: 20px;
    }
}
html.dark {
    .welcome {
        background-color: var(--ba-bg-color-overlay);
    }
    .small-panel {
        background-color: var(--ba-bg-color-overlay);
        .small-panel-content {
            color: var(--el-text-color-regular);
        }
    }
    .new-user-item {
        .new-user-base {
            color: var(--el-text-color-regular);
        }
    }
}
</style>
