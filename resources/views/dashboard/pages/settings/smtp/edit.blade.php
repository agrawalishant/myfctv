@extends('layouts.dashboard.app')
@section('content')
    <div class="content-inner container-fluid pb-0" id="page_layout">
        <div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">SMTP Credentials</h4>
                                &nbsp;
                                <p class="text-warning">Warning: Please do not change email credentials unless you have the proper knowledge to do so. Incorrect changes may result in loss of service or security issues.</p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="error-text text-center text-danger mb-4" id="smtp_error"></div>
                            <form class="form-horizontal" id="smtp_form" method="POST"
                                action="{{ url('dashboard/smtp-settings/update') }}" enctype="multipart/form-data">@csrf
                                <div class="form-group row">
                                    <label class="control-label col-sm-3 align-self-center mb-0"
                                        for="host">Host:</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="host" class="form-control" id="host"
                                            placeholder="mail.example.com" value="{{ $smtpSettings->host ?? old('host') }}">
                                        <span class="text-danger error-text host_error"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-sm-3 align-self-center mb-0"
                                        for="port">Port:</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="port" class="form-control" id="port"
                                            placeholder="587" value="{{ $smtpSettings->port ?? old('port') }}">
                                        <span class="text-danger error-text port_error"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-sm-3 align-self-center mb-0"
                                        for="email">Email:</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="username" class="form-control" id="email"
                                            placeholder="info@example.com"
                                            value="{{ $smtpSettings->username ?? old('username') }}">
                                        <span class="text-danger error-text username_error"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-sm-3 align-self-center mb-0"
                                        for="password">Password:</label>
                                    <div class="col-sm-9">
                                        <input type="password" name="password" class="form-control" id="password"
                                            placeholder="Leave empty to keep the existing password" value="{{ old('password', $smtpSettings->password) }}">
                                        <span class="text-danger error-text password_error" ></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-sm-3 align-self-center mb-0"
                                        for="Encryption">Encryption:</label>
                                    <div class="col-sm-9">
                                        <select class="form-select mb-3 shadow-none" name="encryption">
                                            <option value="ssl"
                                                {{ old('encryption', $smtpSettings->encryption ?? '') === 'ssl' ? 'selected' : '' }}>
                                                SSL</option>
                                            <option value="tls"
                                                {{ old('encryption', $smtpSettings->encryption ?? '') === 'tls' ? 'selected' : '' }}>
                                                TLS</option>
                                        </select>
                                        <span class="text-danger error-text encryption_error"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button id="smtp_submit" type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
