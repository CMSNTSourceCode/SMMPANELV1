$(document).ready(function () {
  'use strict'

  $('[name=server_id]').change(() => {
    const server = getServer()

    if (server === undefined) {
      return Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Cannot find this server',
      })
    }

    const options = server.options

    if (server.info) {
      $('.server_note').html(
        `<label class="form-label">Infomation : </label>
              <div class="alert alert-danger" role="alert" style="background-color:#557C55;color:white">${server.info}</div>`
      )
    } else {
      $('.server_note').html('')
    }

    const reactionLoaded = $('#form_reaction_loaded').val()
    if (options.reaction && !reactionLoaded) {
      axios
        .get(API_GET_FORM + '/reaction')
        .then(({ data: res }) => {
          $('.group_reaction').show().html(res)
        })
        .finally(() => {
          sumPrice()
        })
    } else if (!options.reaction) {
      $('.group_reaction').hide().html('')
    }

    const commentLoaded = $('#form_comment_loaded').val()
    if (options.comments && !commentLoaded) {
      axios
        .get(API_GET_FORM + '/comments')
        .then(({ data: res }) => {
          $('.group_comments').show().html(res)
          sumPrice()
        })
        .finally(() => {
          sumPrice()
        })
    } else if (!options.comments) {
      $('.group_comments').hide().html('')
    }

    console.log(options)

    const vipLikeLoaded = $('#form_viplike_loaded').val()
    if (options.form_type === 'fb_viplike' && !vipLikeLoaded) {
      axios
        .get(API_GET_FORM + '/fb_viplike')
        .then(({ data: res }) => {
          $('.group_fb_viplike').show().html(res)
        })
        .finally(() => {
          sumPrice()
        })
    } else if (options.form_type !== 'fb_viplike') {
      $('.group_fb_viplike').hide().html('')
    }

    // fb_eyeslive
    const eyesLiveLoaded = $('#form_eyeslive_loaded').val()
    if (options.form_type === 'fb_eyeslive' && !eyesLiveLoaded) {
      axios
        .get(API_GET_FORM + '/fb_eyeslive')
        .then(({ data: res }) => {
          $('.group_fb_eyeslive').show().html(res)
        })
        .finally(() => {
          sumPrice()
        })
    } else if (options.form_type !== 'fb_eyeslive') {
      $('.group_fb_eyeslive').hide().html('')
    }

    sumPrice()
  })

  const getServer = () => {
    const server_id = $('[name=server_id]:checked').val()

    return LIST_SERVERS.find((item) => item.id == server_id)
  }

  const getFormJson = () => {
    const form = $('#form-buy')[0]

    const payload = $formDataToPayload(new FormData(form))

    return payload
  }

  // watch change quantity
  $('#quantity').keyup(() => {
    sumPrice()
  })

  const sumPrice = () => {
    const server = getServer()

    if (server === undefined) {
      return 0
    }
    const quantity = parseInt($('#quantity').val()),
      price_per = parseFloat(server.price)

    if (isNaN(quantity) || isNaN(price_per) || quantity <= 0) {
      return 0
    }

    let total_payment = quantity * price_per

    if (server.options.form_type === 'fb_viplike') {
      const num_post = parseInt($('#num_post').val()),
        duration = parseInt($('#duration').val())

      total_payment *= num_post * duration
    } else if (server.options.form_type === 'fb_eyeslive') {
      const duration = parseInt($('#duration').val())

      total_payment *= duration
    }

    if (server.options.charge_by === 'comment_count') {
      const comment_count = parseInt($('.comment_count').text())

      total_payment = comment_count * price_per
    }

    $('.total_price').html($formatCurrency(total_payment))
    $('.total_price_usd').html($formatCurrency(total_payment / EX_RATE, 'USD'))

    return total_payment
  }

  // init to global
  SUM_PRICE_FNC = sumPrice

  //

  $('#form-buy').submit(async (e) => {
    e.preventDefault()

    const confirm = await Swal.fire({
      title: 'You sure?',
      text: 'You want to buy this service ?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, buy it!',
      cancelButtonText: 'Cancel',
    })

    if (confirm.isConfirmed === false) return

    Swal.fire({
      icon: 'warning',
      title: 'Processing...',
      html: 'Please wait a few seconds',
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => {
        Swal.showLoading()
      },
    })

    return handleSubmit(e)
  })

  const handleSubmit = async (e) => {
    const payload = $formDataToPayload(new FormData(e.target)),
      button = e.target.querySelector('button[type=submit]'),
      server = getServer()

    if (payload.object_id === '') {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Please enter a valid object id!',
      })
      return
    }
    if (server === undefined) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Cannot find this server',
      })
      return
    }
    if (payload.quantity <= 0) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Please enter a valid quantity!',
      })
      return
    }

    $setLoading(button)

    try {
      const { data: result } = await axios.post(API_ORDERS, payload)

      Swal.fire('Greate !', result.message, 'success').then(() => {
        // $('#datatable').DataTable().ajax.reload()
        if (GLOBAL_REFRESH) {
          GLOBAL_REFRESH()
        } else {
          window.location.reload()
        }
      })
    } catch (error) {
      if (error?.response?.status === 401) {
        return Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Please login to continue!',
          showCancelButton: true,
          confirmButtonText: 'Login',
          cancelButtonText: 'Cancel',
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = '/login'
          }
        })
      }
      Swal.fire('Oops...', $catchMessage(error), 'error')
    } finally {
      $removeLoading(button)
    }
  }

  $('#btn_get_id').click((e) => {
    e.preventDefault()
    return handleGetID(e)
  })

  const handleGetID = async (e) => {
    const object_id = $('#object_id').val(),
      button = e.target

    if (isNaN(object_id) === false) {
      return false
    }

    if ($isURL(object_id) === false) {
      return Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Please enter a valid URL!',
      })
    }

    $setLoading(button)
    $('#object_id').val('Processing...').attr('disabled', true)

    try {
      const { data: result } = await axios.get(`/api/tools/facebook/get-uid`, {
        params: {
          link: object_id,
        },
      })

      $('#object_id').val(result.data.id)
      Swal.fire('Thành công!', result.message, 'success')
    } catch (error) {
      $('#object_id').val(object_id)
      Swal.fire('Oops...', $catchMessage(error), 'error')
    } finally {
      $('#object_id').attr('disabled', false)
      $removeLoading(button)
    }
  }
})
