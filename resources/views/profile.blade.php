@extends('layout.app')

@section('content')
<!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Account</h4>

              <div class="row">
                <div class="col-md-12">
                  <div class="card mb-4">
                    <h5 class="card-header">Profile Details</h5>
                    <!-- Account -->
                    <!--<div class="card-body">
                      <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <img
                          src="../assets/img/avatars/1.png"
                          alt="user-avatar"
                          class="d-block rounded"
                          height="100"
                          width="100"
                          id="uploadedAvatar"
                        />
                        <div class="button-wrapper">
                          <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                            <span class="d-none d-sm-block">Upload new photo</span>
                            <i class="bx bx-upload d-block d-sm-none"></i>
                            <input
                              type="file"
                              id="upload"
                              class="account-file-input"
                              hidden
                              accept="image/png, image/jpeg"
                            />
                          </label>
                          <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                            <i class="bx bx-reset d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Reset</span>
                          </button>

                          <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                        </div>
                      </div>
                    </div> -->
                    <hr class="my-0" />
                    <div class="card-body">
                      <form id="formAccountSettings" method="POST" onsubmit="return false">
                        <div class="row">
                          <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Nama lengkap</label>
                            <input
                              class="form-control"
                              type="text"
                              id="name"
                              name="name"
                              value="{{ old('name', $user->name) }}"
                              autofocus
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">E-mail</label>
                            <input
                              class="form-control"
                              type="text"
                              id="email"
                              name="email"
                              value="{{ old('email', $user->email) }}"
                              placeholder="john.doe@example.com"
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="no_induk" class="form-label">No Induk</label>
                            <input
                              type="text"
                              class="form-control"
                              id="no_induk"
                              name="no_induk"
                              value="{{ old('no_induk', $user->no_induk) }}"
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label class="form-label" for="phoneNumber">Nomor Telepon</label>
                            <div class="input-group input-group-merge">
                              <span class="input-group-text">+62</span>
                              <input
                                type="text"
                                id="no_hp"
                                name="no_hp"
                                class="form-control"
                                value="{{ old('no_hp', $user->no_hp) }}"
                                placeholder="81234567890"
                              />
                            </div>
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input
                              type="password"
                              id="password"
                              name="password"
                              class="form-control"
                              placeholder="Password"
                            />
                            <small class="form-text text-muted">Isi jika ingin mengubah password.</small>
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input
                              type="password"
                              id="password_confirmation"
                              name="password_confirmation"
                              class="form-control"
                              placeholder="Konfirmasi Password"
                            />
                            <small class="form-text text-muted">Isi jika ingin mengubah password.</small>
                          </div>
                          <div class="mb-3 col-md-12">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control" id="address" name="address" placeholder="Alamat">{{ old('alamat', $user->alamat ?? '') }}</textarea>
                          </div>
                          
                        </div>
                        <div class="mt-2">
                          <button type="submit" class="btn btn-primary me-2">Save changes</button>
                          <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                        </div>
                      </form>
                    </div>
                    <!-- /Account -->
                  </div>
                  <div class="card">
                    <h5 class="card-header">Delete Account</h5>
                    <div class="card-body">
                      <div class="mb-3 col-12 mb-0">
                        <div class="alert alert-warning">
                          <h6 class="alert-heading fw-bold mb-1">Are you sure you want to delete your account?</h6>
                          <p class="mb-0">Once you delete your account, there is no going back. Please be certain.</p>
                        </div>
                      </div>
                      <form id="formAccountDeactivation" onsubmit="return false">
                        <div class="form-check mb-3">
                          <input
                            class="form-check-input"
                            type="checkbox"
                            name="accountActivation"
                            id="accountActivation"
                          />
                          <label class="form-check-label" for="accountActivation"
                            >I confirm my account deactivation</label
                          >
                        </div>
                        <button type="submit" class="btn btn-danger deactivate-account">Deactivate Account</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- / Content -->


            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
@endsection
