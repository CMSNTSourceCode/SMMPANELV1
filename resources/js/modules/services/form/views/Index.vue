<script setup>
import { computed, onMounted, ref, h, watch } from 'vue'
import { NForm, NFormItem, NInput, NSelect, NButton, NGrid, NGridItem, NTooltip, NSpin, NInputNumber, NAvatar, NText, NRadioGroup, NRadio } from 'naive-ui'
import { message } from 'ant-design-vue';
import Swal from 'sweetalert2';


const loading = ref(false)
const platforms = ref([])
const getPlatforms = async () => {
  loading.value = true

  try {
    const { data: result } = await axios.get('/api/preset-data/platforms', { params: { type: 'social', sign: new Date().getTime() } })
    platforms.value = result.data

    await getCategories()
  } catch (error) {
    message.error($catchMessage(error))
  } finally {
    loading.value = false
  }
}

const categories = ref([])
const getCategories = async () => {
  loading.value = true

  try {
    const { data: result } = await axios.get('/api/preset-data/categories', { params: { type: 'social', sign: new Date().getTime() } })

    categories.value = result.data

    await getServices()
  } catch (error) {
    message.error($catchMessage(error))
  } finally {
    loading.value = false
  }
}

const services = ref([])
const getServices = async () => {
  loading.value = true

  try {
    const { data: result } = await axios.get('/api/preset-data/services', { params: { type: 'social', sign: new Date().getTime() } })

    services.value = result.data


    await updateSelect()
  } catch (error) {
    message.error($catchMessage(error))
  } finally {
    loading.value = false
  }
}

const updateSelect = async () => {
  // find platform from platforms with have categories
  const platform = platforms.value.find(platform => categories.value.find(category => category.platform_id === platform.id))

  if (platform) {
    form.value.platform_id = platform.id
    form.value.category_id = categories.value.find(category => category.platform_id === platform.id).id

    form.value.service_id = serviceOptions.value[0]?.value || null
  } else {
    message.error('Không tìm thấy nền tảng nào có dịch vụ')
  }
}

const form = ref({
  search: null,
  service_id: null,
  platform_id: null,
  category_id: null,
  object_id: null,
  quantity: 100,
  comments: null,
  is_multiple: false
})

// format data for select
const platformOptions = computed(() => {
  if (!platforms.value.length)
    return []

  return platforms.value.map(platform => ({
    image: (platform?.image || 'https://cdn.vectorstock.com/i/500p/65/30/default-image-icon-missing-picture-page-vector-40546530.jpg'),
    label: platform.name,
    value: platform.id
  }))
})
const categoryOptions = computed(() => {
  if (!categories.value.length)
    return []

  const data = categories.value.filter(category => category.platform_id === form.value.platform_id).map(category => ({
    image: (category?.image || 'https://cdn.vectorstock.com/i/500p/65/30/default-image-icon-missing-picture-page-vector-40546530.jpg'),
    label: `ID ${category.id} - ${category.name}`,
    value: category.id
  }))

  return data
})
const serviceOptions = computed(() => {
  if (!services.value.length)
    return []

  const data = services.value.filter(service => service.category_id === form.value.category_id).map(service => ({
    image: (service?.image || 'https://static.thenounproject.com/png/4595376-200.png'),
    label: service.display_name,
    value: service.id
  }))

  return data
})
const searchOptions = computed(() => {
  if (!services.value.length)
    return []

  return services.value.map(service => ({
    label: service.display_name,
    value: service.id
  }))
})

const service = computed(() => {
  return services.value.find(service => service.id === form.value.service_id)
})

const totalPrice = computed(() => {
  const pricePer = service.value?.price_per || 0

  let price = form.value.quantity * (service.value?.price_per || 0)

  if (form.value.is_multiple) {
    const objectIds = form.value.object_id?.split('\n').filter(objectId => objectId.trim())

    price = (objectIds?.length || 1) * price
  }

  return price
})

const formatCurrency = (value) => {
  return $formatCurrency(value)
}

const onSearched = (value) => {
  const service = services.value.find(service => service.id === value)

  if (!service) return

  const category = categories.value.find(category => category.id === service.category_id)
  const platform = category?.platform_id
    ? platforms.value.find(platform => platform.id === category.platform_id)
    : null

  form.value = {
    ...form.value,
    platform_id: platform?.id || null,
    category_id: category?.id || null,
    service_id: service.id,
    search: null
  }
  if (form.value.quantity < parseInt(service.min_buy)) {
    form.value.quantity = parseInt(service.min_buy)
  }
}

const updatePlatform = value => {
  const category = categories.value.find(category => category.platform_id === value)

  if (!category) {
    return;
  }

  form.value.platform_id = value
  form.value.category_id = category.id
}

const isComment = computed(() => {
  if (!service.value) return false

  const customComments = ['custom_comments', 'custom_comment']

  return customComments.includes(service.value?.type)
})

const checkComments = value => {
  // split by new line, remove empty string, remove duplicate
  const comments = value.split('\n').filter(comment => comment.trim())

  if (comments.length > 0) {
    form.value.quantity = comments.length
  } else {
    form.value.quantity = 1
  }
}

const checkCategory = value => {
  if (!serviceOptions.value.find(service => service.value === form.value.service_id)) {
    form.value.service_id = serviceOptions.value[0]?.value || null
  }
}

const buying = ref(false)
const onSubmit = async () => {
  buying.value = true

  try {
    const { data: result } = await axios.post('/api/orders/store', form.value)

    Swal.fire({
      icon: 'success',
      title: 'Great!',
      text: result.message,
      showCancelButton: true,
      confirmButtonText: 'View orders',
      cancelButtonText: 'Close',
      cancelButtonColor: '#d33',
      confirmButtonColor: '#3085d6'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = '/account/orders'
      }
    })
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: $catchMessage(error)
    })
  } finally {
    buying.value = false
  }
}

// render
const renderSingleSelectTag = ({ option }) => {
  return h(
    "div",
    {
      style: {
        display: "flex",
        alignItems: "center"
      }
    },
    [
      h(NAvatar, {
        src: option.image,
        round: false,
        size: 24,
        style: {
          flexShrink: 0, // Ngăn avatar co lại
          marginRight: "5px", // Thêm khoảng cách giữa avatar và label
        },
      }),
      option.label
    ]
  );
};
const renderLabel = (option) => {
  return h(
    "div",
    {
      style: {
        display: "flex",
        alignItems: "center"
      }
    },
    [
      h(NAvatar, {
        src: option.image,
        round: false,
        size: "small",
        style: {
          flexShrink: 0, // Ngăn avatar co lại
          marginRight: "2px", // Thêm khoảng cách giữa avatar và label
        },
      }),
      h(
        "div",
        {
          style: {
            marginLeft: "5px",
            padding: "4px 0"
          }
        },
        [
          h("div", {
            style: {
              flex: 1, // Cho phép phần label mở rộng để lấp đầy không gian còn lại
              overflow: "hidden", // Đảm bảo nội dung không tràn ra ngoài
              wordBreak: "break-word", // Cho phép từ xuống dòng khi cần
              whiteSpace: "normal", // Cho phép xuống dòng
              lineHeight: "1.2", // Điều chỉnh khoảng cách giữa các dòng
            }
          }, [option.label])
        ]
      )
    ]
  );
};

watch(() => form.value.is_multiple, (value) => {
  form.value.object_id = null
})

const $__t = (key) => {
  return window.LANG[key] || key
}

onMounted(() => {
  getPlatforms()
})
</script>
<template>
  <n-spin :show="loading">
    <n-form :model="form" @submit.prevent="onSubmit">
      <n-form-item :label="$__t('Tìm nhanh dịch vụ')">
        <n-select v-model:value="form.search" :placeholder="$__t('Tìm kiếm tất cả dịch vụ')" :options="searchOptions" @update:value="onSearched" filterable />
      </n-form-item>
      <n-grid cols="1 s:1 m:2" responsive="screen" :x-gap="16">
        <n-grid-item>
          <n-form-item :label="$__t('Nền tảng')">
            <n-select v-model:value="form.platform_id" :placeholder="$__t('Chọn nền tảng')" :options="platformOptions" :render-label="renderLabel" :render-tag="renderSingleSelectTag" @update:value="updatePlatform"
              filterable></n-select>
          </n-form-item>
        </n-grid-item>
        <n-grid-item>
          <n-form-item :label="$__t('Phân loại')">
            <n-select v-model:value="form.category_id" :placeholder="$__t('Chọn phân loại')" :options="categoryOptions" @update:value="checkCategory" :render-label="renderLabel" :render-tag="renderSingleSelectTag"
              filterable></n-select>
          </n-form-item>
        </n-grid-item>
      </n-grid>
      <n-form-item :label="$__t('Dịch vụ')">
        <n-select v-model:value="form.service_id" :placeholder="$__t('Chọn dịch vụ')" :options="serviceOptions" :render-label="renderLabel" :render-tag="renderSingleSelectTag" filterable></n-select>
      </n-form-item>
      <n-form-item :label="$__t('Liên kết')">
        <n-input v-if="form.is_multiple" v-model:value="form.object_id" :placeholder="$__t('Nhập liên kết cần tăng tương tác, mỗi liên kết / hàng là một đơn')" type="textarea" :row="5"></n-input>
        <n-input v-else v-model:value="form.object_id" :placeholder="$__t('Nhập liên kết cần tăng tương tác')"></n-input>
      </n-form-item>
      <div class="text-center mb-3">
        <n-radio-group v-model:value="form.is_multiple">
          <n-radio :label="false" :value="false">{{ $__t('Một liên kết') }}</n-radio>
          <n-radio :label="true" :value="true">{{ $__t('Nhiều liên kết') }}</n-radio>
        </n-radio-group>
      </div>
      <n-form-item :label="$__t('Số lượng')">
        <n-input-number v-model:value="form.quantity" :placeholder="$__t('Nhập số lượng tương tác')" :min="service ? parseInt(service?.min_buy || 1) : 1" :max="service ? parseInt(service?.max_buy || null) : null"
          clearable :readonly="isComment" style="width: 100%" />
      </n-form-item>
      <n-form-item v-if="isComment" :label="$__t('Nội dung')">
        <n-input v-model:value="form.comments" :placeholder="$__t('Nhập nội dung bình luận tuỳ chọn')" type="textarea" :rows="5" @update:value="checkComments"></n-input>
      </n-form-item>
      <div class="mb-3">
        <label for="average_time" class="form-label">{{ $__t('Average time') }}</label>
        <n-input :placeholder="$__t('Chưa có dữ liệu...')" :value="service?.average_time || $__t('Chưa có dữ liệu')" disabled></n-input>
        <small class="text-muted fw-bold mt-2">* {{ $__t('Thời gian trung bình dựa trên 10 đơn hàng hoàn thành với số lượng lớn hơn 1000.') }}</small>
      </div>
      <div class="mb-3">
        <div class="text-center price_box">
          <div class="price_box--bg">
            <span class="total_price">{{ formatCurrency(totalPrice) }}</span>
          </div>
        </div>
      </div>
      <div v-if="service && service.descr" class="mb-3">
        <label for="descr" class="form-label">{{ $__t('Thông tin dịch vụ') }}</label>
        <div class="alert alert-warning" v-html="service.descr"></div>
      </div>
      <n-form-item>
        <n-button type="error" block :loading="buying" attr-type="submit" size="large"><i class="fas fa-credit-card me-2"></i> {{ $__t('THANH TOÁN NGAY') }}</n-button>
      </n-form-item>
    </n-form>
  </n-spin>
</template>
<style scoped>
.price_box {
  border-radius: 8px;
}

.price_box--bg {
  background-color: #f1f1f1;
  padding: 10px;
  border-radius: 8px;
}

.price_box .total_price {
  background-color: #fff8f8;
  /* Nền trắng hồng nhạt */
  padding: 0 10px;
  border-radius: 5px;
  color: #ff0000;
  /* Màu đỏ tươi nổi bật */
  font-size: 35px;
  font-weight: bold;
  border: 1px solid #ffe8e8;
  /* Viền hồng nhạt */
}
</style>