@extends('admin.layouts.master')
@section('title', $pageTitle)
@section('css')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
@endsection
@section('content')
  <div class="mb-3 text-end">
    <button data-bs-toggle="modal" data-bs-target="#modal-create" class="btn btn-outline-primary me-2"><i class="fas fa-plus"></i> {{ __t('Thêm mới') }}</button>
    <a href="{{ route('admin.providers.import-services') }}" class="btn btn-outline-primary me-2"><i class="fas fa-list"></i> {{ __t('Nhập dịch vụ') }}</a>
    <button class="btn btn-danger-gradient action-ids" onclick="deleteOrder()"><i class="fas fa-trash me-2"></i> {{ __t('Xoá') }}</button>
    <button class="btn btn-primary-gradient action-ids" onclick="changeCategory()"><i class="fas fa-edit me-2"></i> {{ __t('Đổi chuyên mục') }}</button>
  </div>

  <div class="card custom-card">
    <div class="card-header justify-content-between">
      <div class="card-title">Danh sách dịch vụ</div>
    </div>
    <div class="card-body">
      <div class="text-center mb-3">
        <div class="row justify-content-center">

          <div class="col-md-4">
            <div class="mb-3">
              <label for="category_id" class="form-label">Chuyên mục : </label>
              <select name="category_id" id="category_id" class="form-select js-category">
                <option value="">Tất cả</option>
                @foreach ($categories as $service)
                  <option value="{{ $service->id }}">ID {{ $service->id }} - {{ $service->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-4">
            <div class="mb-3">
              <label for="provider_id" class="form-label">API Provider : </label>
              <select name="provider_id" id="provider_id" class="form-select js-provider">
                <option value="">Tất cả</option>
                @foreach ($providers as $provider)
                  <option value="{{ $provider->id }}">ID {{ $provider->id }} - {{ $provider->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary w-100" id="btn_reload" onclick='$("#datatable").DataTable().ajax.reload()' style="margin-top: 25px">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>

      </div>
      <div class="table-responsive theme-scrollbar" style="padding: 10px">
        <table class="display table table-bordered table-stripped text-nowrap datatable-custom122" id="datatable">
          <thead>
            <tr>
              <th>#</th>
              <th>
                <input type="checkbox" name="checked_all">
              </th>
              <th>{{ __t('Action') }}</th>
              <th>{{ __t('Name') }}</th>
              <th>{{ __t('Image') }}</th>
              <th>{{ __t('Provider') }}</th>
              <th>{{ __t('Category') }}</th>
              <th>{{ __t('Type') }}</th>
              <th>{{ __t('Rate Per 1k') }}</th>
              <th>{{ __t('Min/Max') }}</th>
              <th>{{ __t('Description') }}</th>
              <th>{{ __t('Status') }}</th>
              <th>{{ __t('Created At') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach ([] as $value)
              <tr>
                <td>{{ $value->id }}</td>
                <th>
                  <input type="checkbox" name="checked_ids[]" value="{{ $value->id }}}">
                </th>
                <td>
                  <a href="{{ route('admin.services.show', ['id' => $value->id]) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></a>
                  <a href="javascript:void(0)" class="btn btn-outline-danger btn-sm" onclick="deleteRow({{ $value->id }})"><i class="fas fa-trash"></i></a>
                </td>
                <td>{{ $value->name }}</td>
                <td class="text-center">
                  <div>
                    {{ $value->provider?->name ?? '-' }}
                  </div>
                  <small class="text-danger">{{ $value->api_service_id ?? '-' }}</small>
                </td>
                <td>{{ $value->api_service_id }}</td>
                <td class="text-start">{{ $value->category?->name ?? '-' }}</td>
                <td>{{ $value->type }}</td>
                <td>{{ show_price_format($value->price) }}</td>
                <td>{{ number_format($value->min_buy) }}/{{ number_format($value->max_buy) }}</td>
                <td>{{ truncate_string($value->descr, 20) }}</td>
                <td>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault{{ $value->id }}" value="{{ $value->id }}" @if ($value->status) checked @endif
                      onchange="updateStatus(this)">
                    <label class="form-check-label" for="flexSwitchCheckDefault{{ $value->id }}"></label>
                  </div>
                </td>
                <td>{{ $value->created_at }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Thêm thông tin mới</h5>
          <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('admin.services.store') }}" method="POST" class="default-form" data-reload="1" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
              <label for="name" class="form-label">Tên dịch vụ</label>
              <input class="form-control" type="text" id="name" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
              <label for="image" class="form-label">Hình ảnh</label>
              <input class="form-control" type="file" accept="image/*" id="image" name="image" value="{{ old('image') }}">
            </div>
            <div class="mb-3">
              <label for="category" class="form-label">Chuyên mục</label>
              <select name="category_id" id="category_id" class="form-control jsmodal-category_id">
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}" @if (old('category_id') == $category->id) selected @endif>ID {{ $category->id }} : {{ $category->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label for="mode" class="form-label">Chế độ</label>
              <select name="mode" id="mode" class="form-control">
                <option value="api">API</option>
                <option value="manual" selected>Manaul</option>
                <option value="option">Option</option>
              </select>
            </div>
            <div class="mb-3 card bg-secondary mode_form"></div>
            <div class="mb-3 row">
              <div class="col-md-4">
                <label for="min_buy" class="form-label">Mua ít nhất</label>
                <input type="number" class="form-control" name="min_buy" id="min_buy" value="{{ old('min_buy') }}" required>
              </div>
              <div class="col-md-4">
                <label for="max_buy" class="form-label">Mua tối đa</label>
                <input type="number" class="form-control" name="max_buy" id="max_buy" value="{{ old('max_buy') }}" required>
              </div>
              <div class="col-md-4">
                <label for="price" class="form-label">Tỷ lệ trên 1000</label>
                <input type="text" class="form-control" name="price" id="price" value="{{ old('price', 0) }}" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="status" class="form-label">Trạng thái</label>
              <select class="form-control" id="status" name="status">
                <option value="1" @if (old('status') == 1) selected @endif>Hoạt động</option>
                <option value="0" @if (old('status') == 0) selected @endif>Không hoạt động</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="descr" class="form-label">Ghi chú</label>
              <textarea name="descr" id="descr" class="form-control" rows="5">{{ old('descr') }}</textarea>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary w-100" type="submit">Thêm mới</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $("[name=checked_all]").change(function(e) {
      if ($(this).is(":checked")) {
        $("[name='checked_ids[]']").prop("checked", true)
      } else {
        $("[name='checked_ids[]']").prop("checked", false)
      }
    })

    function getIds() {
      let ids = []
      $("[name='checked_ids[]']:checked").each(function() {
        ids.push($(this).val())
      })
      return ids
    }

    // find class actions-ids set disabled with getIds() < 0, and set length checked-ids
    function setActions() {
      let ids = getIds()
      console.log(ids)
      if (ids.length > 0) {
        $(".action-ids").prop("disabled", false)
        $(".checked-ids").text(ids.length)
      } else {
        $(".action-ids").prop("disabled", true)
        $(".checked-ids").text(0)
      }
    }

    $(document)
      .ready(function() {
        setActions();
      })
      .on('change', 'input[name="checked_all"]:enabled', function() {
        setActions();
      })
      .on('change', 'input[name="checked_ids[]"]:enabled', function() {
        setActions();
      })
  </script>
  <script>
    window._providers = @json($providers);
    window._categories = @json($categories);

    $(document).ready(function() {
      $(".js-category").select2({
        placeholder: "{{ __t('Tìm kiếm chuyên mục') }}",
        allowClear: true,
        dir: "ltr",
        selectionCssClass: 'select2-selection--single',
      });
      $(".js-provider").select2({
        placeholder: "{{ __t('Tìm kiếm nhà cung cấp') }}",
        allowClear: true,
        dir: "ltr",
        selectionCssClass: 'select2-selection--single',
      });

      $('#modal-create').on('shown.bs.modal', function() {
        $(this).find('.jsmodal-category_id').select2({
          placeholder: "Tìm kiếm tài khoản",
          allowClear: true,
          dir: 'ltr',
          dropdownParent: $('#modal-create')
        });
      });
    });


    //
    const updateStatus = (element) => {
      let id = element.value;
      let status = element.checked ? 1 : 0;

      axios.post(`/admin/services/update-status`, {
        id: id,
        status: !!status
      }).then((response) => {
        Swal.fire({
          icon: 'success',
          title: 'Thành công',
          text: response.data.message
        })
      }).catch((error) => {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: $catchMessage(error)
        })
      });
    }

    const deleteRow = async (id) => {
      const confirmDelete = await Swal.fire({
        title: '{{ __t('Bạn chắc chứ?') }}',
        text: "{{ __t('Bạn sẽ không thể khôi phục lại dữ liệu này!') }}",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '{{ __t('Xóa') }}',
        cancelButtonText: '{{ __t('Hủy') }}'
      });

      if (!confirmDelete.isConfirmed) return;

      $showLoading();

      try {
        const {
          data: result
        } = await axios.post('{{ route('admin.services.delete') }}', {
          id
        })

        Swal.fire('Thành công', result.message, 'success').then(() => {
          window.location.reload();
        })
      } catch (error) {
        Swal.fire('Thất bại', $catchMessage(error), 'error')
      }
    }

    const deleteOrder = async () => {
      let ids = getIds()

      if (ids.length === 0) {
        Swal.fire('Thất bại', '{{ __t('Vui lòng chọn ít nhất 1 dòng để xóa') }}', 'error')
        return
      }

      const confirm = await Swal.fire({
        title: '{{ __t('Bạn chắc chứ?') }}',
        text: "{{ __t('Bạn sẽ không thể khôi phục lại dữ liệu này!') }}",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '{{ __t('Xóa') }}',
        cancelButtonText: '{{ __t('Hủy') }}'
      })

      if (!confirm.isConfirmed)
        return

      $showLoading();

      try {
        const {
          data: result
        } = await axios.post('{{ route('admin.services.delete') }}', {
          ids
        })

        Swal.fire('Thành công', result.message, 'success').then(() => {
          window.location.reload();
        })
      } catch (error) {
        Swal.fire('Thất bại', $catchMessage(error), 'error')
      }
    }

    const changeCategory = async () => {
      let ids = getIds()

      if (ids.length === 0) {
        Swal.fire('Thất bại', '{{ __t('Vui lòng chọn ít nhất 1 dòng để thay đổi') }}', 'error')
        return
      }

      const categoryOptions = window._categories.reduce((acc, category) => {
        acc[category.id] = `ID ${category.id} - ${category.name}`
        return acc
      }, {})

      const category_id = await Swal.fire({
        icon: 'question',
        title: '{{ __t('Chọn chuyên mục mới') }}',
        input: 'select',
        inputOptions: categoryOptions,
        inputPlaceholder: '{{ __t('Chọn chuyên mục') }}',
        showCancelButton: true,
        inputValidator: (value) => {
          if (!value) {
            return '{{ __t('Vui lòng chọn chuyên mục') }}'
          }
        }
      })

      if (!category_id.value) {
        Swal.fire('Thất bại', '{{ __t('Vui lòng nhập ID chuyên mục mới') }}', 'error')
        return
      }

      $showLoading();

      try {
        const {
          data: result
        } = await axios.post('{{ route('admin.services.change-category') }}', {
          ids,
          category_id: category_id.value
        })

        Swal.fire('Thành công', result.message, 'success').then(() => {
          window.location.reload();
        })
      } catch (error) {
        Swal.fire('Thất bại', $catchMessage(error), 'error')
      }
    }

    $(document).ready(function() {
      $('.datatable-custom').DataTable({
        "order": [
          [0, "desc"]
        ],
        "columnDefs": [{
          "targets": [1, 2, 10],
          "orderable": false,
        }]
      });

      function confirmPost() {
        return confirm('Bạn có chắc chắn muốn xóa thông tin này không?');
      }

      const updateMode = () => {
        let mode = $("#mode").val(),
          formContent = '',
          formElement = $(".mode_form");

        axios.get(`/admin/services/load-forms/${mode}`).then((response) => {
          formContent = response.data;
          formElement.html(formContent);
        }).catch((error) => {
          console.error(error);
        });
      }

      $("#mode").change(() => {
        updateMode();
      })

      updateMode();

      //
      const $table = $('#datatable')

      const $tableOptions = {
        processing: true,
        serverSide: true,
        ajax: {
          url: '/api/admin/services',
          type: 'GET',
          headers: {
            Authorization: `Bearer ${userData?.access_token}`,
          },
          data: (data) => {
            let payload = {}
            // default params
            payload.category_id = $('#category_id').val()
            payload.provider_id = $('#provider_id').val()
            // set params
            payload.page = data.start / data.length + 1
            payload.limit = data.length
            payload.search = data.search.value
            payload.sort_by = data.columns[data.order[0].column].data
            payload.sort_type = data.order[0].dir
            // return json
            return payload
          },
          beforeSend: function(xhr) {
            $setLoading($('#btn_reload'))
          },
          error: function(xhr) {
            console.log(xhr?.responseJSON)
          },
          dataFilter: function(data) {
            let json = JSON.parse(data)
            if (json.status) {
              json.recordsTotal = json.data.meta.total
              json.recordsFiltered = json.data.meta.total
              json.data = json.data.data
              return JSON.stringify(json) // return JSON string
            } else {
              Swal.fire('Thất bại', json.message, 'error')

              return JSON.stringify({
                recordsTotal: 0,
                recordsFiltered: 0,
                data: [],
              })
            }
          },
        },
        columns: [{
            data: 'id'
          },
          {
            data: null,
            render: (data, type, row) => {
              return `<input type="checkbox" name="checked_ids[]" value="${row.id}">`
            },
            sortable: false,
          }, {
            data: null,
            render: (data) => {
              return `<a href="/admin/services/edit/${data.id}" class="badge bg-primary-gradient"><i class="fas fa-edit"></i></a>
              <a href="javascript:deleteRow(${data.id})" class="shadow text-white badge bg-danger-gradient"><i class="fa fa-trash"></i></a>`
            },
          }, {
            data: 'name',
            render: (data) => {
              return $truncate(data, 80)
            }
          },
          {
            data: 'image',
            render: (data) => {
              return `<img src="${data}" alt="image" class="img-fluid" style="max-width: 30px">`
            }
          },
          {
            data: 'provider',
            className: 'text-center',
            render: (data, type, row) => {
              return `<div>${data?.name ?? row?.api_provider_id ?? '-'}</div><div class="text-danger">${row.api_service_id}</div>`
            }
          },
          {
            data: 'category',
            render: (data) => {
              return data?.name ?? '-'
            }
          },
          {
            data: 'type'
          },
          {
            data: 'price',
            render: (data, type, row) => {
              return $formatCurrency(data)
            }
          },
          {
            data: null,
            render: (data) => {
              return `${$formatNumber(data.min_buy)}/${$formatNumber(data.max_buy)}`
            }
          },
          {
            data: 'descr',
            render: (data) => {
              return $truncate(data, 20)
            }
          },
          {
            data: 'status',
            render: (data, type, row) => {
              return `<div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault${row.id}" value="${row.id}" onchange="updateStatus(this)" ${data?'checked':''}>
                  <label class="form-check label" for="flexSwitchCheckDefault${row.id}"></label>
                </div>`
            }
          },
          {
            data: 'created_at',
            render: (data) => {
              return $formatDate(data)
            }
          }
        ],
        columnDefs: [{
          targets: [1, 2, 3, 4, 5],
          orderable: false,
          sortable: false,
        }],
        order: [
          [0, 'desc']
        ],
        lengthMenu: [
          [10, 20, 50, 100, 500, 1000, 5000],
          [10, 20, 50, 100, 500, 1000, 5000],
        ],
        pageLength: 20,

      }

      const $tableInstance = $table.DataTable($tableOptions)

      $tableInstance.on('draw.dt', function() {
        $removeLoading($('#btn_reload'))
        $('[data-bs-toggle="tooltip"]').tooltip()
      })
    })
  </script>
@endsection
