<script setup>
import { computed, onMounted, ref } from 'vue'
import { usePagination } from 'vue-request'
import axios from 'axios'
import moment from 'moment'
import { message } from 'ant-design-vue';

const search = ref('')
const columns = [
  {
    title: 'ID',
    dataIndex: 'id',
    sorter: true,
  },
  {
    title: 'Thao Tác',
    dataIndex: 'action',
  },
  {
    title: 'Hoá Đơn',
    dataIndex: 'code',
  },
  {
    title: 'Số Tiền',
    dataIndex: 'amount',
  },
  {
    title: 'Tài Khoản',
    dataIndex: 'username',
  },
  {
    title: 'Còn Lại',
    dataIndex: 'expired_str',
  },
  {
    title: 'Trạng Thái',
    dataIndex: 'status',
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
]

const queryData = (params) => {
  return axios.get('/api/users/invoices', {
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

const open = ref(false);

const showModal = () => {
  open.value = true;
};

const formatCurrency = (value) => {
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value)
}

const banks = ref([])

const getBanks = async () => {
  try {
    const { data: result } = await axios.get('/api/users/banks')

    banks.value = result.data?.map(bank => ({
      ...bank,
      label: `${bank.name} - ${bank.owner} - ${bank.number}`,
      value: bank.id,
    }))
  } catch (error) {
    message.error($catchMessage(error))
  }
}

const form = ref({
  amount: 10000,
  bank_id: null,
  channel: 'banking'
})

const submiting = ref(false)
const onSubmit = async (value) => {
  if (!value.bank_id) {
    message.error('Vui Lòng Chọn Ngân Hàng Để Nạp')
    return
  }

  submiting.value = true

  try {
    const { data: result } = await axios.post('/api/users/invoices', { ...value, channel: 'banking' })

    message.success(result.message)

    open.value = false

    refresh()
  } catch (error) {
    message.error($catchMessage(error))
  } finally {
    submiting.value = false
  }
}

const openPayment = ref(false)
const paymentInfo = ref(null)
const showPayment = async (record) => {
  if (!record.id) {
    return
  }

  openPayment.value = false

  message.loading('Đang Tải Thông Tin Hoá Đơn...', 0)

  try {
    const { data: result } = await axios.get('/api/users/invoices/' + record.id)

    const invoice = result.data

    if (invoice.status !== 'processing') {
      Swal.fire('Thành Công', 'Hoá Đơn Đã Được Cập Nhật Rồi', 'success')
      return refresh()
    }

    paymentInfo.value = {
      id: invoice.id,
      code: invoice.code,
      bank: (invoice.payment_details),
      status: invoice.status,
      amount: invoice.amount,
    }

    openPayment.value = true
  } catch (error) {
    message.error($catchMessage(error))
  } finally {
    message.destroy()
  }

}

const generateQrCode = (value) => {

  const { bank } = value

  return `https://api.vietqr.io/${bank.name}/${bank.number}/${value.amount}/${value.code}/vietqr_net_2.jpg?accountName=${bank.owner}`
}

onMounted(() => {
  getBanks()
})
</script>

<template>
  <div>
    <a-card title="Danh Sách Hoá Đơn">
      <div class="table-responsive">
        <div class="mb-3 d-flex justify-content-between">
          <div class="me-2"> <a-button type="dashed" danger @click="showModal"><i class="fas fa-plus me-2"></i> Tạo Hoá Đơn Mới</a-button></div>
          <div class="d-flex" style="align-items: flex-end;">
            <a-input v-model:value="search" placeholder="Tìm kiếm" class="me-2" />
            <a-button type="primary" :loading="loading" @click="refresh">
              Tìm kiếm
            </a-button>
          </div>
        </div>
        <a-table :dataSource="dataSource" :columns="columns" :loading="loading" :pagination="pagination" size="small" @change="handleTableChange" class="font-medium whitespace-nowrap">
          <template #bodyCell="{ column, text, record }">
            <template v-if="column.dataIndex === 'action'">
              <a-button v-if="record.status === 'processing'" size="small" @click="showPayment(record)"><i class="fas fa-credit-card me-2"></i> Thanh Toán</a-button>
            </template>
            <template v-if="column.dataIndex === 'amount'">
              <strong class="text-green-700">{{ formatCurrency(text) }}</strong>
            </template>
            <template v-if="column.dataIndex === 'status'">
              <span v-if="text === 'processing'" class="font-bold text-warning-600">Đang Chờ</span>
              <span v-else-if="text === 'completed'" class="font-bold text-green-700">Hoàn Thành</span>
              <span v-else-if="text === 'cancelled'" class="font-bold text-red-700">Bị Huỷ/Xoá</span>
              <span v-else class="font-bold text-gray-700">{{ text }}</span>
            </template>
            <template v-if="column.dataIndex === 'expired_str'">
              <strong v-if="record.is_expired" class="text-red-700">-</strong>
              <strong v-else class="text-primary-600">{{ text }}</strong>
            </template>
            <template v-if="column.dataIndex === 'created_at'">{{
            formatDate(text)
          }}</template>
            <template v-if="column.dataIndex === 'updated_at'">{{
            formatDate(text)
          }}</template>
          </template>
        </a-table>
      </div>
    </a-card>

    <a-modal v-model:open="open" :footer="null">
      <a-form :model="form" layout="vertical" @finish="onSubmit">
        <a-form-item label="Số Tiền Nạp" name="amount" :rules="[{ required: true, message: 'Vui Lòng Nhập Số Tiền Cần Nạp' }]">
          <a-input-number v-model:value="form.amount" :min="10000" :max="1000000000" style="width: 100%" size="large" />
        </a-form-item>
        <a-form-item label="Chọn Ngân Hàng" name="bank_id" :rules="[{ required: true, message: 'Vui Lòng Chọn Ngân Hàng Để Nạp' }]">
          <a-select v-model:value="form.bank_id" placeholder="Chọn Ngân Hàng">
            <a-select-option v-for="bank in banks" :key="bank.value" :value="bank.value" size="large">
              {{ bank.label }}
            </a-select-option>
          </a-select>
        </a-form-item>
        <a-form-item>
          <div class="text-center" style="font-size: 25px">
            <strong class="text-danger">{{ formatCurrency(form.amount) }}</strong>
          </div>
        </a-form-item>
        <a-form-item>
          <a-button type="primary" htmlType="submit" block :loading="submiting">Tạo Ngay</a-button>
        </a-form-item>
      </a-form>
    </a-modal>

    <a-modal width="900px" v-model:open="openPayment" :footer="null">
      <a-alert type="error" message="Quý khách vui lòng chuyển khoản với nội dung là mã Hoá đơn đến số tài khoản bên dưới hoặc quét Mã QR dưới đây để nạp tiền." style="margin-bottom: 25px; margin-top: 25px"></a-alert>
      <div v-if="paymentInfo" class="row">
        <div class="col-md-6">
          <div style="margin-top: 30%">
            <div class="d-flex justify-content-between">
              <div class="me-2">Mã Hoá Đơn:</div>
              <div class="text-danger">{{ paymentInfo.code }} <a href="javascript:void(0)" class="copy" :data-clipboard-text="paymentInfo.code"><i class="fas fa-copy"></i></a></div>
            </div>
            <div class="d-flex justify-content-between">
              <div class="me-2">Ngân Hàng:</div>
              <div class="text-success">{{ paymentInfo.bank.name }}</div>
            </div>
            <div class="d-flex justify-content-between">
              <div class="me-2">Số Tài Khoản:</div>
              <div class="text-danger">{{ paymentInfo.bank.number }} <a href="javascript:void(0)" class="copy" :data-clipboard-text="paymentInfo.bank.number"><i class="fas fa-copy"></i></a></div>
            </div>
            <div class="d-flex justify-content-between">
              <div class="me-2">Chủ Tài Khoản:</div>
              <div class="text-success">{{ paymentInfo.bank.owner }}</div>
            </div>
            <div class="d-flex justify-content-between">
              <div class="me-2">Số Tiền Chuyển:</div>
              <div class="text-danger">{{ formatCurrency(paymentInfo.amount) }} <a href="javascript:void(0)" class="copy" :data-clipboard-text="paymentInfo.amount"><i class="fas fa-copy"></i></a></div>
            </div>
            <div class="text-center">
              <a-button @click="showPayment({ id: paymentInfo.id })">Cập Nhật</a-button>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <img :src="generateQrCode(paymentInfo) " />
        </div>
      </div>
    </a-modal>
  </div>
</template>