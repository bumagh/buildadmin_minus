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