<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="float-start">Listado de Repartidores</h5>
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
                                    <th>Repartidor</th>
                                    <th>Teléfono</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in repartidores" :key="item.id">
                                    <td>{{ item.nombre }}</td>
                                    <td>{{ item.telefono }}</td>
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
    <div class="modal fade" id="repartidorModal" tabindex="-1" aria-labelledby="repartidorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5" id="repartidorModalLabel">{{ formTitle }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!--definiendo cuerpo del formulario local-->
                    <div class="row">
                        <div class="form-group col-12">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" v-model="repartidor.nombre">
                            <span class="text-danger" v-show="repartidorErrors.nombre">Nombre es requerido</span>
                        </div>
                        <div class="form-group col-12">
                            <label for="Telefono">Telefono</label>
                            <input type="number" class="form-control" v-model="repartidor.telefono">
                            <span class="text-danger" v-show="repartidorErrors.telefono">Teléfono es requerido</span>
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
            repartidores: [],
            repartidor: {
                id: null,
                nombre: "",
                telefono: ""
            },
            editedRepartidor: -1,
            repartidorErrors: {
                nombre: false,
                telefono: false
            },
        }
    },
    computed: {
        formTitle() {
            return this.repartidor.id == null ? "Agregar Repartidor" : "Actualizar Repartidor";
        },
        btnTitle() {
            return this.repartidor.id == null ? "Guardar" : "Actualizar";
        }
    },
    methods: {
        async fetchRepartidores() {
            let me = this;
            await this.axios.get('/repartidores')
                .then(response => {
                    //console.log(response.data);
                    me.repartidores = response.data
                })
        },
        showDialog() {
            this.repartidor = {
                id: null,
                nombre: "",
                telefono: ""
            };
            this.repartidorErrors = {
                nombre: false,
                telefono: false
            }
            $('#repartidorModal').modal('show');
        },
        showDialogEditar(repartidor) {
            let me = this;
            $('#repartidorModal').modal('show');
            me.editedRepartidor = me.repartidores.indexOf(repartidor);
            me.repartidor = Object.assign({}, repartidor);
        },
        hideDialog() {
            let me = this;
            setTimeout(() => {
                me.repartidor = {
                    id: null,
                    nombre: "",
                    telefono: ""
                }
            }, 300)
            $('#repartidorModal').modal('hide');
        },
        async saveOrUpdate() {
            let me = this;
            console.log('Entro');
            me.repartidor.nombre == '' ? me.repartidorErrors.nombre = true : me.repartidorErrors.nombre = false;
            me.repartidor.telefono == '' ? me.repartidorErrors.telefono = true : me.repartidorErrors.telefono = false;
            if (me.repartidor.nombre) {
                let accion = me.repartidor.id == null ? "add" : "upd";

                if (accion == "add") {
                    await this.axios.post('/repartidores', me.repartidor)
                        .then(response => {
                            console.log(response.status);
                            if (response.status == 200) {
                                me.verficarAccion(response.data.data, response.status, accion);
                                me.hideDialog();
                            }
                        }).catch(errors => {
                            console.log(errors);
                        })
                } else {
                    await this.axios.put(`/repartidores/${me.repartidor.id}`, me.repartidor)
                        .then(response => {
                            //console.log(response.data);
                            console.log(response.status);
                            if (response.status == 200) {
                                me.verficarAccion(response.data.data, response.status, accion);
                                me.hideDialog();
                            }
                        }).catch(errors => {
                            console.log(errors);
                        })
                }
            }
        },
        async eliminar(repartidor) {
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
                    me.editedRepartidor = me.repartidores.indexOf(repartidor);
                    this.axios.delete(`/repartidores/${repartidor.id}`)
                        .then(response => {
                            me.verficarAccion(null, response.status, "del");
                        }).catch(errors => {
                            console.log(errors);
                        })
                }
            })
        },
        verficarAccion(repartidor, statusCode, accion) {
            let me = this;
            const Toast = this.$swal.mixin({
                toast: true,
                position: 'top-right',
                iconColr: 'white',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
            switch (accion) {
                case "add":
                    me.repartidores.unshift(repartidor);
                    Toast.fire({
                        icon: 'success',
                        'title': 'Repartidor Registrado con Exito...!'
                    })
                    break;

                case "upd":
                    Object.assign(me.repartidores[me.editedRepartidor], repartidor);
                    Toast.fire({
                        icon: 'success',
                        'title': 'Repartidor Actualizado con Exito...!'
                    })
                    break;
                case "del":
                    if (statusCode == 200) {
                        me.repartidores.splice(this.editedRepartidor, 1);
                        Toast.fire({
                            icon: 'success',
                            'title': 'Repartidor eliminado...'
                        });

                    } else {
                        this.$swal.fire({
                            icon: 'error',
                            'title': 'No se puede eliminar'
                        });
                    }
                    break;
            }
        },
    },

    mounted() {
        //this.$swal('Hello Vue world!!!');
        this.fetchRepartidores();
    }
}
</script>
