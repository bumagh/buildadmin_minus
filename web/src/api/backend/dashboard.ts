import { CoinLog } from '/@/stores/interface'
import createAxios from '/@/utils/axios'

export const url = '/admin/Dashboard/'
export function dashboard() {
    return createAxios({
        url: url + 'dashboard',
        method: 'get',
    })
}

export function getAgentCount() {
    return createAxios({
        url: url + 'getAgentCount',
        method: 'get',
    })
}

export function addCoinLog(data:CoinLog) {
    return createAxios(
        {
            url: '/admin/agent.coinLog/add',
            method: 'post',
            data: data,
        },
        {
            showSuccessMessage: true,
        }
    )
}
