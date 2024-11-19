// Path: resources/js/app.js
'use strict'
// !!! Author : contact@quocbao.dev
// !!! Date : 2022-11-20
// !!! Description : Utils for any project

console.log(`info: v1.0.1`)
// core functions & require jquery
window.$getResponseMessage = function (error) {
  if (error.response && error.response.data) {
    return error.response.data.message
  }
  return error.message || 'Unknown error'
}

window.$getRequestMessage = function (error) {
  return error.message || 'Error in request'
}

window.$getStatusMessage = function (error) {
  if (error.responseJSON) {
    return error.responseJSON.message
  }
  return error.statusText
}

window.$getErrorMessage = function (error) {
  return error.message || error.stack
}

window.$catchMessage = function (error) {
  let message = 'System error occurred'

  message = error.isAxiosError
    ? error.response
      ? $getResponseMessage(error)
      : error.request
      ? $getRequestMessage(error)
      : message
    : error.status
    ? $getStatusMessage(error)
    : $getErrorMessage(error)

  console.log(error.response || error.request || error)

  return message
}

// window.$formatCurrency = function (number, currency = 'VND', maxinum = 2) {
//   if (currency === 'VND') {
//     return number.toLocaleString('vi-VN', {
//       style: 'currency',
//       currency: 'VND',
//     })
//   }

//   const formatter = new Intl.NumberFormat('en-US', {
//     minimumFractionDigits: 0,
//     maximumFractionDigits: 6,
//   })

//   return formatter.format(number) + (currency === 'VND' ? ' ₫' : ' $')
// }

window.$formatNumber = function (number) {
  return new Intl.NumberFormat('en-US').format(number)
}

window.$formatDateTime = function (date, format = 'YYYY-MM-DD HH:mm:ss') {
  return moment(date).format(format)
}

window.$formatStatus = function (status) {
  switch (status) {
    case 'Running':
      return `<span class="badge bg-primary">Đang chạy</span>`
    case 'Pending':
      return `<span class="badge bg-warning">Đang chờ</span>`
    case 'Preparing':
      return `<span class="badge bg-info">Đang chuẩn bị</span>`
    case 'Canceled':
      return `<span class="badge bg-danger">Đã hủy</span>`
    case 'Completed':
      return `<span class="badge bg-success">Chạy xong</span>`
    case 'Refund':
      return `<span class="badge bg-danger">Hoàn tiền</span>`
    case 'WaitingForRefund':
      return `<span class="badge bg-secondary">Đang huỷ</span>`
    case 'Holding':
      return `<span class="badge bg-warning">Đang giữ</span>`
    case 'Paused':
      return `<span class="badge bg-danger">Tạm dừng</span>`
    case 'Expired':
      return `<span class="badge bg-danger">Hết hạn</span>`
    case 'Active':
      return `<span class="badge bg-success">Hoạt động</span>`
    case 'Warranty':
      return `<span class="badge bg-info">Bảo hành</span>`
    default:
      return `<span class="badge bg-secondary">${status}</span>`
  }
}

window.$setLoading = function (elm) {
  $(elm).attr('disabled', true).addClass('process')
}

window.$removeLoading = function (elm) {
  $(elm).attr('disabled', false).removeClass('process')
}

window.$formatDate = function (date, format = 'YYYY-MM-DD HH:mm:ss') {
  return moment(date).format(format)
}

window.$isURL = function (str) {
  let regex =
    /(http|https):\/\/(\w+:?\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/
  let pattern = new RegExp(regex)
  return pattern.test(str)
}

window.$truncate = function (str, length = 100, ending = '...') {
  if (str.length > length) {
    return str.substring(0, length - ending.length) + ending
  } else {
    return str
  }
}

window.$swal = function (type, message, options = {}) {
  return Swal.fire({
    icon: type === 'success' ? 'success' : 'error',
    title: type === 'success' ? 'Thành công' : 'Thất bại',
    text: message,
    ...options,
  })
}

window.$showLoading = function (message = null) {
  Swal.fire({
    icon: 'info',
    title: 'Đang xử lý!',
    html: 'Không được tắt trang này, vui lòng đợi trong giây lát!',
    timerProgressBar: true,
    allowOutsideClick: false,
    allowEscapeKey: false,
    allowEnterKey: false,
    didOpen: () => {
      Swal.showLoading()
    },
    willClose: () => {},
  })
}

window.$showMsg = function (type, message, options = {}) {
  return Swal.mixin({
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: 2000,

    timerProgressBar: true,
  }).fire({
    icon: type === 'success' ? 'success' : 'error',
    title: type === 'success' ? 'Thành công' : 'Thất bại',
    text: message,
    ...options,
  })
}

window.$hideLoading = function () {
  Swal.close()
}

window.$base64_decode = function (str) {
  // Going backwards: from bytestream, to percent-encoding, to original string.
  return decodeURIComponent(
    atob(str)
      .split('')
      .map(function (c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)
      })
      .join('')
  )
}

window.$getCountryName = function (code) {
  if (!code) {
    return '-'
  }
  return new Intl.DisplayNames(['en'], { type: 'region' }).of(
    code?.toUpperCase()
  )
}

window.$formDataToPayload = function (data) {
  const payload = {}
  for (let [key, value] of data.entries()) {
    payload[key] = value
  }
  return payload
}

window.$ucfirst = function (string) {
  return string.charAt(0).toUpperCase() + string.slice(1)
}
// network checking
window.addEventListener('online', function (e) {
  console.log('online')
  $swal('success', 'Đã kết nối mạng')
})
window.addEventListener('offline', function (e) {
  console.log('offline')
  $swal('error', 'Mất kết nối mạng')
})
// image error
// function imgError(e) {
//   let img = e.target
//   img.removeEventListener('error', imgError)
//   img.src = '/images/missing.svg'
// }

// document.querySelectorAll('img').forEach((img) => {
//   if (img.naturalWidth === 0) {
//     img.addEventListener('error', imgError)
//     img.src = img.src
//   }
// })

// copy function
let clipboard = new ClipboardJS('.copy')

clipboard.on('success', function (e) {
  toastr.success('Copied : ' + e.text)
})

clipboard.on('error', function (e) {
  toastr.error('Failed to copy')
})
// variables
window.$userLevelName = function (level) {
  return level.charAt(0).toUpperCase() + level.slice(1)
}

// extra
window.$logout = async function () {
  try {
    const result = await axios.post('/logout')
  } finally {
    window.location.href = '/login'
  }
}
