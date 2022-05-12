<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div v-if="loading" class="ui inverted active dimmer">
                        <div class="ui active loader"></div>
                    </div>
                    <div class="card-body">
                        <h4>Montevista</h4>
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-hospital" viewBox="0 0 16 16">
                                      <path d="M8.5 5.034v1.1l.953-.55.5.867L9 7l.953.55-.5.866-.953-.55v1.1h-1v-1.1l-.953.55-.5-.866L7 7l-.953-.55.5-.866.953.55v-1.1h1ZM13.25 9a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5ZM13 11.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-.5Zm.25 1.75a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5Zm-11-4a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5A.25.25 0 0 0 3 9.75v-.5A.25.25 0 0 0 2.75 9h-.5Zm0 2a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5ZM2 13.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-.5Z"/>
                                      <path d="M5 1a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1a1 1 0 0 1 1 1v4h3a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h3V3a1 1 0 0 1 1-1V1Zm2 14h2v-3H7v3Zm3 0h1V3H5v12h1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3Zm0-14H6v1h4V1Zm2 7v7h3V8h-3Zm-8 7V8H1v7h3Z"/>
                                    </svg>
                                </i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Stock</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0" v-text="mont"></h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6" v-for="(row, item) in actualMon">
                                <p class="mt-1 mb-0">
                                    <i aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                          <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                        </svg>
                                    </i>
                                    {{ item }} ({{row.length}})
                                </p>
                            </div>
                        </div>
                        <hr />
                        <small class="text-danger">Near Expiry</small>
                        <div class="row">
                            <div class="col-6" v-for="(row, item) in nearExpiryMon">
                                <p class="mt-1 mb-0">
                                    <i aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                          <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                        </svg>
                                    </i>
                                    {{ item }} ({{row.length}})
                                </p>
                            </div>
                        </div>
                        <button
                            @click="showNearExpiryDetails('mon')"
                            type="button"
                            class="btn btn-primary btn-xs"
                            data-toggle="modal"
                            data-target="#expiryModal"
                        >Details</button>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div v-if="loading" class="ui inverted active dimmer">
                        <div class="ui active loader"></div>
                    </div>
                    <div class="card-body">
                        <h4>Maragusan</h4>
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="text-warning">
                                    <i class="text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-hospital" viewBox="0 0 16 16">
                                          <path d="M8.5 5.034v1.1l.953-.55.5.867L9 7l.953.55-.5.866-.953-.55v1.1h-1v-1.1l-.953.55-.5-.866L7 7l-.953-.55.5-.866.953.55v-1.1h1ZM13.25 9a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5ZM13 11.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-.5Zm.25 1.75a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5Zm-11-4a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5A.25.25 0 0 0 3 9.75v-.5A.25.25 0 0 0 2.75 9h-.5Zm0 2a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5ZM2 13.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-.5Z"/>
                                          <path d="M5 1a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1a1 1 0 0 1 1 1v4h3a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h3V3a1 1 0 0 1 1-1V1Zm2 14h2v-3H7v3Zm3 0h1V3H5v12h1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3Zm0-14H6v1h4V1Zm2 7v7h3V8h-3Zm-8 7V8H1v7h3Z"/>
                                        </svg>
                                    </i>
                                </i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Stock</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0" v-text="mara"></h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6" v-for="(row, item) in actualMar">
                                <p class="mt-1 mb-0">
                                    <i aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                          <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                        </svg>
                                    </i>
                                    {{ item }} ({{row.length}})
                                </p>
                            </div>
                        </div>
                        <hr />
                        <small class="text-danger">Near Expiry</small>
                        <div class="row">
                            <div class="col-6" v-for="(row, item) in nearExpiryMar">
                                <p class="mt-1 mb-0">
                                    <i aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                          <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                        </svg>
                                    </i>
                                    {{ item }} ({{row.length}})
                                </p>
                            </div>
                        </div>
                        <button
                            @click="showNearExpiryDetails('mar')"
                            type="button"
                            class="btn btn-primary btn-xs"
                            data-toggle="modal"
                            data-target="#expiryModal"
                        >Details</button>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div v-if="loading" class="ui inverted active dimmer">
                        <div class="ui active loader"></div>
                    </div>
                    <div class="card-body">
                        <h4>Laak</h4>
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="text-success">
                                    <i class="text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-hospital" viewBox="0 0 16 16">
                                          <path d="M8.5 5.034v1.1l.953-.55.5.867L9 7l.953.55-.5.866-.953-.55v1.1h-1v-1.1l-.953.55-.5-.866L7 7l-.953-.55.5-.866.953.55v-1.1h1ZM13.25 9a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5ZM13 11.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-.5Zm.25 1.75a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5Zm-11-4a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5A.25.25 0 0 0 3 9.75v-.5A.25.25 0 0 0 2.75 9h-.5Zm0 2a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5ZM2 13.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-.5Z"/>
                                          <path d="M5 1a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1a1 1 0 0 1 1 1v4h3a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h3V3a1 1 0 0 1 1-1V1Zm2 14h2v-3H7v3Zm3 0h1V3H5v12h1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3Zm0-14H6v1h4V1Zm2 7v7h3V8h-3Zm-8 7V8H1v7h3Z"/>
                                        </svg>
                                    </i>
                                </i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Stock</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{ laak }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6" v-for="(row, item) in actualLaa">
                                <p class="mt-1 mb-0">
                                    <i aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                          <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                        </svg>
                                    </i>
                                    {{ item }} ({{row.length}})
                                </p>
                            </div>
                        </div>
                        <hr />
                        <small class="text-danger">Near Expiry</small>
                        <div class="row">
                            <div class="col-6" v-for="(row, item) in nearExpiryLaa">
                                <p class="mt-1 mb-0">
                                    <i aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                          <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                        </svg>
                                    </i>
                                    {{ item }} ({{row.length}})
                                </p>
                            </div>
                        </div>
                        <button
                            @click="showNearExpiryDetails('laa')"
                            type="button"
                            class="btn btn-primary btn-xs"
                            data-toggle="modal"
                            data-target="#expiryModal"
                        >Details</button>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div v-if="loading" class="ui inverted active dimmer">
                        <div class="ui active loader"></div>
                    </div>
                    <div class="card-body">
                        <h4>Pantukan</h4>
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="text-info">
                                    <i class="text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-hospital" viewBox="0 0 16 16">
                                          <path d="M8.5 5.034v1.1l.953-.55.5.867L9 7l.953.55-.5.866-.953-.55v1.1h-1v-1.1l-.953.55-.5-.866L7 7l-.953-.55.5-.866.953.55v-1.1h1ZM13.25 9a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5ZM13 11.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-.5Zm.25 1.75a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5Zm-11-4a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5A.25.25 0 0 0 3 9.75v-.5A.25.25 0 0 0 2.75 9h-.5Zm0 2a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5ZM2 13.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-.5Z"/>
                                          <path d="M5 1a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1a1 1 0 0 1 1 1v4h3a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h3V3a1 1 0 0 1 1-1V1Zm2 14h2v-3H7v3Zm3 0h1V3H5v12h1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3Zm0-14H6v1h4V1Zm2 7v7h3V8h-3Zm-8 7V8H1v7h3Z"/>
                                        </svg>
                                    </i>
                                </i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Stock</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{ pant }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6" v-for="(row, item) in actualPan">
                                <p class="mt-1 mb-0">
                                    <i aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                          <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                        </svg>
                                    </i>
                                    {{ item }} ({{row.length}})
                                </p>
                            </div>
                        </div>
                        <hr />
                        <small class="text-danger">Near Expiry</small>
                        <div class="row">
                            <div class="col-6" v-for="(row, item) in nearExpiryPan">
                                <p class="mt-1 mb-0">
                                    <i aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                          <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                        </svg>
                                    </i>
                                    {{ item }} ({{row.length}})
                                </p>
                            </div>
                        </div>
                        <button
                            @click="showNearExpiryDetails('pan')"
                            type="button"
                            class="btn btn-primary btn-xs"
                            data-toggle="modal"
                            data-target="#expiryModal"
                        >Details</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div class="card-body">
                        <h4>Near Expiry</h4>
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="text-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-alarm-fill" viewBox="0 0 16 16">
                                      <path d="M6 .5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1H9v1.07a7.001 7.001 0 0 1 3.274 12.474l.601.602a.5.5 0 0 1-.707.708l-.746-.746A6.97 6.97 0 0 1 8 16a6.97 6.97 0 0 1-3.422-.892l-.746.746a.5.5 0 0 1-.707-.708l.602-.602A7.001 7.001 0 0 1 7 2.07V1h-.5A.5.5 0 0 1 6 .5zm2.5 5a.5.5 0 0 0-1 0v3.362l-1.429 2.38a.5.5 0 1 0 .858.515l1.5-2.5A.5.5 0 0 0 8.5 9V5.5zM.86 5.387A2.5 2.5 0 1 1 4.387 1.86 8.035 8.035 0 0 0 .86 5.387zM11.613 1.86a2.5 2.5 0 1 1 3.527 3.527 8.035 8.035 0 0 0-3.527-3.527z"/>
                                    </svg>
                                </i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Stock</p>
                                <div class="fluid-container">
                                    <h3
                                        class="font-weight-medium text-right mb-0"
                                    >{{ near_expire.length }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="mt-1 mb-0" v-for="(row, item) in actual_expire">
                            <i aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                  <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                </svg>
                            </i>
                            {{ item }} ({{row.length}})
                        </p>
                    </div>
                </div>
            </div>

            <!-- <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                    <div class="card card-statistics">
                        <div class="card-body">
                            <h4>Expired</h4>
                            <div class="clearfix">
                                <div class="float-left">
                                    <i class="fa fa-clock-o text-danger icon-lg"></i>
                                </div>
                                <div class="float-right">
                                    <p class="mb-0 text-right">Stock</p>
                                    <div class="fluid-container">
                                        <h3
                                            class="font-weight-medium text-right mb-0"
                                        >{{ expired.length }}</h3>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-1 mb-0" v-for="(row, item) in actual_near_expire">
                                <i class="fa fa-check" aria-hidden="true"></i>
                                {{ item }} ({{row.length}})
                            </p>
                        </div>
                    </div>
            </div>-->
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div class="card-body">
                        <h4>Pending Orders</h4>
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="text-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-list-check" viewBox="0 0 16 16">
                                      <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3.854 2.146a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2 3.293l1.146-1.147a.5.5 0 0 1 .708 0zm0 4a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2 7.293l1.146-1.147a.5.5 0 0 1 .708 0zm0 4a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z"/>
                                    </svg>
                                </i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Stock</p>
                                <div class="fluid-container">
                                    <h3
                                        class="font-weight-medium text-right mb-0"
                                        v-text="pendingOrders"
                                    ></h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0">
                            <!-- <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i> Weekly Sales -->
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div class="card-body">
                        <h4>Total</h4>
                        <div class="clearfix">
                            <div class="float-left">
                                <i class="text-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-droplet-fill" viewBox="0 0 16 16">
                                      <path d="M8 16a6 6 0 0 0 6-6c0-1.655-1.122-2.904-2.432-4.362C10.254 4.176 8.75 2.503 8 0c0 0-6 5.686-6 10a6 6 0 0 0 6 6ZM6.646 4.646l.708.708c-.29.29-1.128 1.311-1.907 2.87l-.894-.448c.82-1.641 1.717-2.753 2.093-3.13Z"/>
                                    </svg>
                                </i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Stock</p>
                                <div class="fluid-container">
                                    <h3
                                        class="font-weight-medium text-right mb-0"
                                        v-text="stock.length"
                                    ></h3>
                                </div>
                            </div>
                        </div>
                        <!-- <p class="mt-1 mb-0" v-for="(row, item) in actualStocks">
                                <i class="fa fa-check" aria-hidden="true"></i>
                                {{ item }} - {{row.length}}
                        </p>-->
                        <line-chart :chart-data="datacollection"></line-chart>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div
            class="modal fade"
            id="expiryModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Near Expiry</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <td>Type</td>
                                    <td>Component</td>
                                    <td>Serial</td>
                                    <td>Expiry Date</td>
                                </tr>
                            </thead>
                            <tbody v-for="(row1, item1) in showExpiries">
                                <tr v-for="(row2, item2) in row1">
                                    <td>{{ row2.blood_type.description }}</td>
                                    <td>{{ row2.blood_component.description }}</td>
                                    <td>{{row2.serial}}</td>
                                    <td>{{row2.date_expiry}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <create-disposition></create-disposition>
    </div>
</template>
<script>
import CreateDisposition from "./dispositions/create.vue";
import LineChart from "./LineChart.js";
import { mapState } from "vuex";

export default {
    props: [
        "pending_orders",
        "near_expire",
        "actual_expire",
        "expired",
        "actual_near_expire"
    ],
    components: {
        CreateDisposition,
        LineChart
    },
    data() {
        return {
            mont: 0,
            laak: 0,
            mara: 0,
            pant: 0,
            stock: 0,
            showExpiries: [],
            nearExpiryMon: [],
            nearExpiryLaa: [],
            nearExpiryMar: [],
            nearExpiryPan: [],
            actualMon: [],
            actualLaa: [],
            actualMar: [],
            actualPan: [],
            actualStocks: [],
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    scales: {
                        xAxes: [{ ticks: { mirror: true } }]
                    }
                }
            },
            datacollection: null,
            loading: false
        };
    },
    computed: {
        ...mapState(["user", "pendingOrders"])
    },

    async mounted() {
        this.$store.dispatch("toggleLoading", true);
        this.$store.dispatch("countPendingOrders");
        this.loading = !this.loading;
        await axios.post("all-stocks", this.user).then(response => {
            let stocks = response.data;
            let self = this;
            var nearExpiryMon = [];
            var nearExpiryLaa = [];
            var nearExpiryMar = [];
            var nearExpiryPan = [];

            _.forEach(stocks, function(stock) {
                if (stock.prefix == "MON") {
                    //montevista
                    self.mont = stock.dispositions_count;
                    self.actualMon = _.groupBy(
                        stock.dispositions,
                        "blood_type.description"
                    );
                    _.forEach(stock.dispositions, function(dispositions) {
                        if (self.nearExpire(dispositions.date_expiry)) {
                            nearExpiryMon.push(dispositions);
                        }
                    });
                    self.nearExpiryMon = _.groupBy(
                        nearExpiryMon,
                        "blood_type.description"
                    );
                } else if (stock.prefix == "LAA") {
                    //laak
                    self.laak = stock.dispositions_count;
                    self.actualLaa = _.groupBy(
                        stock.dispositions,
                        "blood_type.description"
                    );
                    _.forEach(stock.dispositions, function(dispositions) {
                        if (self.nearExpire(dispositions.date_expiry)) {
                            nearExpiryLaa.push(dispositions);
                        }
                    });
                    self.nearExpiryLaa = _.groupBy(
                        nearExpiryLaa,
                        "blood_type.description"
                    );
                } else if (stock.prefix == "MAR") {
                    //maragusan
                    self.mara = stock.dispositions_count;
                    self.actualMar = _.groupBy(
                        stock.dispositions,
                        "blood_type.description"
                    );
                    _.forEach(stock.dispositions, function(dispositions) {
                        if (self.nearExpire(dispositions.date_expiry)) {
                            nearExpiryMar.push(dispositions);
                        }
                    });
                    self.nearExpiryMar = _.groupBy(
                        nearExpiryMar,
                        "blood_type.description"
                    );
                } else if (stock.prefix == "PAN") {
                    //pantukan
                    self.pant = stock.dispositions_count;
                    self.actualPan = _.groupBy(
                        stock.dispositions,
                        "blood_type.description"
                    );
                    _.forEach(stock.dispositions, function(dispositions) {
                        if (self.nearExpire(dispositions.date_expiry)) {
                            nearExpiryPan.push(dispositions);
                        }
                    });
                    self.nearExpiryPan = _.groupBy(
                        nearExpiryPan,
                        "blood_type.description"
                    );
                }
            });
            this.loading = !this.loading;
        });

        await axios.post("total-stocks", this.user).then(response => {
            this.stock = response.data;
            this.actualStocks = _.groupBy(this.stock, "blood_type.description");

            let self = this;
            let chart = [];

            var dynamicColors = function() {
                var r = Math.floor(Math.random() * 255);
                var g = Math.floor(Math.random() * 255);
                var b = Math.floor(Math.random() * 255);
                return "rgb(" + r + "," + g + "," + b + ")";
            };

            _.forEach(this.actualStocks, function(value, key) {
                chart.push({ 
                    label: key, 
                    backgroundColor: dynamicColors(),
                    data: [value.length]
                });
            });

            this.fillData(chart);
            this.$emit("notify");
        });
    },
    methods: {
        fillData(chart) {
            this.datacollection = {
                labels: [""],
                datasets: chart,
                tooltips: false
            };

            console.log(this.datacollection)
        },

        nearExpire(date) {
            const startDate = date;
            const endDate = new Date();
            const timeDiff = new Date(startDate) - endDate;
            const days = timeDiff / (1000 * 60 * 60 * 24);

            if (days <= 10 && days >= 0) {
                return true;
            }

            return false;
        },

        showNearExpiryDetails(details) {
            if (details == "mon") {
                this.showExpiries = this.nearExpiryMon;
            } else if (details == "mar") {
                this.showExpiries = this.nearExpiryMar;
            } else if (details == "laa") {
                this.showExpiries = this.nearExpiryLaa;
            } else if (details == "pan") {
                this.showExpiries = this.nearExpiryPan;
            }
        }
    }
};
</script>
