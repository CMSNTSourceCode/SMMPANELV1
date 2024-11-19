<script setup>
import { computed, ref } from 'vue'
import { usePagination } from 'vue-request'
import axios from 'axios'
import moment from 'moment'
import { ReloadOutlined } from '@ant-design/icons-vue'

const search = ref('')
const columns = [
  {
    title: 'ID',
    dataIndex: 'id',
    sorter: true,
  },
  {
    title: 'Mã Giao Dịch',
    dataIndex: 'code',
    width: '8%'
  },
  {
    title: 'Số Dư Trước',
    dataIndex: 'balance_before',
  },
  {
    title: 'Số Tiền',
    dataIndex: 'amount',
  },
  {
    title: 'Số Dư Sau',
    dataIndex: 'balance_after',
  },
  {
    title: 'Tài Khoản',
    dataIndex: 'username',
  },
  {
    title: 'Thời Gian',
    dataIndex: 'created_at',
    sorter: true,
  },
  {
    title: 'Cập Nhật',
    dataIndex: 'updated_at',
    sorter: true,
  },
  {
    title: 'Nội Dung',
    dataIndex: 'content',
    width: '20%'
  },
]

const queryData = (params) => {
  return axios.get('/api/users/transactions', {
    params: {
      search: search.value,
      ...params,
    },
  })
}

const { data, current, totalPage, loading, pageSize, run, refresh } =
  usePagination(queryData, {
    formatResult: (res) => {
      const { data, meta } = res.data.data

      return {
        data: data ?? [],
        totalPage: meta.total_rows ?? 0,
      }
    },
    defaultParams: [
      {
        sort_by: 'id',
        sort_type: 'desc',
        limit: 10,
      },
    ],
    pagination: {
      currentKey: 'page',
      pageSizeKey: 'limit',
    },
  })

const formatDate = (date, format = null) => {
  if (date === null) {
    return ''
  }
  const parsedDate = moment(date, moment.ISO_8601)
  if (!parsedDate.isValid()) {
    return ''
  }
  if (format) {
    return parsedDate.format(format)
  }
  return parsedDate.format('HH:mm:ss - DD/MM/YYYY')
}

const formatCurrency = (number, currency = 'VND', maxinum = 2) => {
  const formatter = new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 2,
  })

  return formatter.format(number) + ' ₫'
}

const dataSource = computed(() => data.value?.data ?? [])

const pagination = computed(() => ({
  page: current.value,
  total: totalPage.value,
  limit: pageSize.value,
}))

const handleTableChange = (pag, filters, sorter) => {
  run({
    page: pag?.current,
    limit: pag.pageSize,
    sort_by: sorter.field,
    sort_type: sorter.order === 'ascend' ? 'asc' : 'desc',
    ...filters,
  })
}
</script>

<template>
  <div class="overflow-auto">
    <div class="mb-3 d-flex flex-col gap-5 md:flex-row md:items-center">
      <div class="ltr:ml-auto rtl:mr-auto d-flex gap-x-2">
        <a-input v-model:value="search" placeholder="Tìm kiếm" class="me-1" />
        <a-button type="primary" :loading="loading" @click="refresh">
          Tìm kiếm
        </a-button>
      </div>
    </div>
    <a-table :dataSource="dataSource" :columns="columns" :loading="loading" :pagination="pagination" size="small" @change="handleTableChange" class="font-medium whitespace-nowrap">
      <template #bodyCell="{ column, text, record }">
        <template v-if="column.dataIndex === 'code'">
          <b style="color: #191D88" class="copy cursor-pointer" :data-clipboard-text="text">{{ text }}</b>
        </template>
        <template v-if="column.dataIndex === 'balance_before'">{{
          formatCurrency(text)
        }}</template>
        <template v-if="column.dataIndex === 'amount'">
          <span :class="record.prefix === '-' ? 'text-danger' : 'text-success'">{{ record.prefix }} {{ formatCurrency(text) }}</span>
        </template>
        <template v-if="column.dataIndex === 'balance_after'">{{
          formatCurrency(text)
        }}</template>
        <template v-if="column.dataIndex === 'created_at'">{{
          formatDate(text)
        }}</template>
        <template v-if="column.dataIndex === 'updated_at'">{{
          formatDate(text)
          }}</template>
      </template>
    </a-table>
  </div>
</template>