import createAxios from '/@/utils/axios'

export const url = '/admin/agent.coinLog/'

export function add(agentId: number) {
    return createAxios({
        url: url + 'add',
        method: 'get',
        params: {
            agentId: agentId,
        },
    })
}
