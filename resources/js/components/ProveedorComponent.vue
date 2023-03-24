<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="float-start">Listado de Proveedores</h5>
                            </div>
                            <div class="col-6">
                                <button @click="showDialog" class="btn btn-success btn-sm float-end">Nuevo</button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table bordered">
                            <thead>
                                <tr>
                                    <th>Proveedores</th>
                                    <th>Contacto</th>
                                    <th>Dirección</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in proveedores" :key="item.id">
                                    <td>{{ item.nombre }}</td>
                                    <td>{{ item.contacto }}</td>
                                    <td>{{ item.direccion }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm"
                                            @click="showDialogEditar(item)">Editar</button>
                                        &nbsp;
                                        <button type="button" class="btn btn-danger btn-sm"
                                            @click="eliminar(item)">Eliminar</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="proveedorModal" tabindex="-1" aria-labelledby="proveedorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5" id="proveedorModalLabel">{{ formTitle }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!--definiendo cuerpo del formulario local-->
                    <div class="row">
                        <div class="form-group col-12">
                            <label for="nombre">Proveedor</label>
                            <input type="text" class="form-control" v-model="proveedor.nombre">
                            <span class="text-danger" v-show="proveedorErrors.nombre">Nombre es requerido</span>
                        </div>
                        <div class="form-group col-12">
                            <label for="contacto">Contacto</label>
                            <input type="number" class="form-control" v-model="proveedor.contacto">
                            <span class="text-danger" v-show="proveedorErrors.contacto">Contacto es requerido</span>
                        </div>
                        <div class="form-group col-12">
                            <label for="direccion">Dirección</label>
                            <input type="text" class="form-control" v-model="proveedor.direccion">
                            <span class="text-danger" v-show="proveedorErrors.direccion">Dirección es requerido</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" @click="saveOrUpdate()">{{ btnTitle }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            proveedores:[],
            proveedor: {
                id: null,
                nombre: "",
                contacto: "",
                direccion: "",
            },
            editedProveedor: -1,
            proveedorErrors: {
                nombre: false,
                contacto: false,
                direccion: false
            },
        }
    },
    computed: {
        formTitle() {
            return this.proveedor.id == null ? "Agregar Proveedor" : "Actualizar Proveedor";
        },
        btnTitle() {
            return this.proveedor.id == null ? "Guardar" : "Actualizar";
        }
    },
    methods: {
        async fetchProveedores() {
            let me = this;
            await this.axios.get('/proveedores')
                .then(response => {
                    //console.log(response.data);
                    me.proveedores = response.data
                })
        },
        showDialog() {
            this.proveedor = {
                id: null,
                nombre: "",
                contacto: "",
                direccion: ""
            };
            this.proveedorErrors = {
                nombre: false,
                contacto: false,
                direccion: false
            }
            $('#proveedorModal').modal('show');
        },
        showDialogEditar(proveedor) {
            let me = this;
            $('#proveedorModal').modal('show');
            me.editedProveedor = me.proveedores.indexOf(proveedor);
            me.proveedor = Object.assign({}, proveedor);
        },
        hideDialog() {
            let me = this;
            setTimeout(() => {
                me.proveedor = {
                    id: null,
                    nombre:"",
                    contacto: "",
                    direccion: "",
                }
            }, 300);
            $('#proveedorModal').modal('hide');
        },
        async saveOrUpdate() {
            let me = this;
            me.proveedor.nombre == '' ? me.proveedorErrors.nombre = true : me.proveedorErrors.nombre = false;
            me.proveedor.contacto == '' ? me.proveedorErrors.contacto = true : me.proveedorErrors.contacto = false;
            me.proveedor.direccion == '' ? me.proveedorErrors.direccion = true : me.proveedorErrors.direccion = false;

            if (me.proveedor.nombre) {
                let accion = me.proveedor.id == null ? "add" : "upd";
                if (accion == "add") {
                    await this.axios.post('/proveedores', me.proveedor)
                        .then(response => {
                            //console.log(response.data);
                            if (response.status == 200) {
                            me.verificarAccion(response.data.data, response.status, accion);
                            me.hideDialog();
                            }
                        }).catch(errors => {
                            console.log(errors);
                        })
                } else {
                    await this.axios.put(`/proveedores/${me.proveedor.id}`, me.proveedor)
                        .then(response => {
                            //console.log(response.data);
                            if (response.status == 200) {
                            me.verificarAccion(response.data.data, response.status, accion);
                            me.hideDialog();
                            }
                        }).catch(errors => {
                            console.log(errors);
                        })

                }
            }
        },
        async eliminar(proveedor) {
            let me = this;
            this.$swal.fire({
                title: 'Seguro/a de eliminar este registro?',
                text: 'No podrás revertir la acción',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "Si",
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.value) {
                    me.editedProveedor = me.proveedores.indexOf(proveedor);
                    this.axios.delete(`/proveedores/${proveedor.id}`)
                        .then(response => {
                            me.verificarAccion(null, response.status, "del");
                        }).catch(errors => {
                            console.log(errors);
                        })
                }
            })
        },
        verificarAccion(proveedor, statusCode, accion) {
            let me = this;
            const Toast = this.$swal.mixin({
                toast: true,
                position: 'top-right',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });

            switch (accion) {
                case "add":
                    me.proveedores.unshift(proveedor);
                    Toast.fire({
                        icon: 'success',
                        'title': 'Proveedor registrada con exito...!'
                    });
                    break;

                case "upd":
                    Object.assign(me.proveedores[me.editedProveedor], proveedor);
                    Toast.fire({
                        icon: 'success',
                        'title': 'Proveedor actualizado con exito...!'
                    });
                    break;
                case "del":
                    if (statusCode == 200) {
                        me.proveedores.splice(this.editedProveedor, 1);
                        Toast.fire({
                            icon: 'success',
                            'title': 'Proveedor eliminado con exito...!'
                        });
                    } else {
                        Toast.$swal.fire({
                            icon: 'warning',
                            'title': 'Ocurrió un error, intente de nuevo...!'
                        });
                    }
                    break;

            }
        }
    },
    mounted() {
        //console.log('Component mounted.');
        this.fetchProveedores();
    }
}
</script>
