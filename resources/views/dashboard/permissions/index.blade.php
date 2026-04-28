@extends('dashboard.layouts.master')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <div>
                <h4 class="mb-1">الصلاحيات</h4>
                <p class="text-body-secondary mb-0">عرض الصلاحيات وتعديل صلاحيات الأدوار</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @php
            $editableRoles = $roles->filter(fn($r) => $r->slug !== 'super_admin');
            $firstEditableRole = $editableRoles->first();
            $rolePermissionsMap = $roles->mapWithKeys(fn($r) => [$r->id => $r->permissions->pluck('id')->toArray()]);
        @endphp

        <div class="row">
            <div class="col-12 col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">تعديل صلاحيات الدور</h5>
                    </div>
                    <div class="card-body">
                        @if ($firstEditableRole)
                            <form action="{{ route('dashboard.permissions.update') }}" method="POST" id="permission-form">
                                @csrf
                                <input type="hidden" name="role_id" id="role_id" value="{{ $firstEditableRole->id }}">
                                <div class="mb-3">
                                    <label class="form-label">اختر الدور</label>
                                    <select class="form-select" id="role_select">
                                        @foreach ($editableRoles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="permissions-container">
                                    @foreach ($permissions as $group => $items)
                                        <div class="mb-3">
                                            <strong class="d-block mb-2 text-primary">{{ $group }}</strong>
                                            @foreach ($items as $permission)
                                                <div class="form-check">
                                                    <input class="form-check-input perm-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="p_{{ $permission->id }}">
                                                    <label class="form-check-label" for="p_{{ $permission->id }}">{{ $permission->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                                @if (auth('admin')->user()->hasPermission('permissions.edit'))
                                    <button type="submit" class="btn btn-primary mt-3">حفظ الصلاحيات</button>
                                @endif
                            </form>
                        @else
                            <p class="text-body-secondary mb-0">لا توجد أدوار قابلة للتعديل. دور مدير النظام له صلاحيات كاملة.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">جميع الصلاحيات حسب المجموعة</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>المجموعة</th>
                                        <th>الصلاحية</th>
                                        <th>المعرّف</th>
                                        <th>عدد الأدوار</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissions as $group => $items)
                                        @foreach ($items as $permission)
                                            <tr>
                                                <td>{{ $group }}</td>
                                                <td>{{ $permission->name }}</td>
                                                <td><code>{{ $permission->slug }}</code></td>
                                                <td>{{ $permission->roles_count }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (isset($firstEditableRole) && $firstEditableRole)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const roleSelect = document.getElementById('role_select');
                const roleIdInput = document.getElementById('role_id');
                const rolePermissions = @json($rolePermissionsMap);
                const checkboxes = document.querySelectorAll('.perm-checkbox');

                function updateCheckboxes() {
                    const roleId = roleSelect?.value;
                    if (!roleId) return;
                    const permIds = rolePermissions[roleId] || [];
                    checkboxes.forEach(cb => {
                        cb.checked = permIds.includes(parseInt(cb.value));
                    });
                }

                if (roleSelect) {
                    roleSelect.addEventListener('change', function() {
                        roleIdInput.value = this.value;
                        updateCheckboxes();
                    });
                    updateCheckboxes();
                }
            });
        </script>
    @endif
@endsection
