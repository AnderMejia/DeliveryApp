<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="float-start">Listado de Productos</h5>
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
                                    <th>Producto</th>
                                    <th>Descripción</th>
                                    <th>Proveedor</th>
                                    <th>Precio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in productos" :key="item.id">
                                    <td>{{ item.nombre }}</td>
                                    <td>{{ item.descripcion }}</td>
                                    <td>{{ item.proveedor.nombre }}</td>
                                    <td>{{ item.precio }}</td>

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
    <div class="modal fade" id="productoModal" tabindex="-1" aria-labelledby="productoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5" id="productoModalLabel">{{ formTitle }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!--definiendo cuerpo del formulario local-->
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="tipo">Proveedor</label>
                            <select v-model="producto.proveedor_id" class="form-control">
                                <option v-for="prove in proveedores" :value="prove.id">
                                    {{ prove.nombre }}
                                </option>
                            </select>
                            <span class="text-danger" v-show="productoErrors.proveedor">Seleccione proveedor</span>
                        </div>
                        <div class="form-group col-6">
                            <label for="nombre">Producto</label>
                            <input type="text" class="form-control" v-model="producto.nombre">
                            <span class="text-danger" v-show="productoErrors.nombre">Nombre es requerido</span>
                        </div>
                        <div class="form-group col-6">
                            <label for="descripcion">Descripción</label>
                            <input type="text" class="form-control" v-model="producto.descripcion">
                            <span class="text-danger" v-show="productoErrors.descripcion">Descripción es requerido</span>
                        </div>
                        <div class="form-group col-6">
                            <label for="precio">Precio</label>
                            <input type="number" class="form-control" v-model="producto.precio">
                            <span class="text-danger" v-show="productoErrors.precio">Dirección es requerido</span>
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
            productos: [],
            producto: {
                id: null,
                nombre: "",
                descripcion: "",
                proveedor_id: null,
                proveedor: null,
                precio: "",

            },
            editedProducto: -1,
            productoErrors: {
                nombre: false,
                descripcion: false,
                proveedor: false,
                precio: false,


            },
            proveedores: []
        }
    },
    computed: {
        formTitle() {
            return this.producto.id == null ? "Agregar Producto" : "Actualizar Producto";
        },
        btnTitle() {
            return this.producto.id == null ? "Guardar" : "Actualizar";
        }
    },
    methods: {
        async fetchProductos() {
            let me = this;
            await this.axios.get('/productos')
                .then(response => {
                    console.log(response.data);
                    me.productos = response.data
                })
        },
        async fetchProveedores() {
            let me = this;
            await this.axios.get('/proveedores')
                .then(response => {
                    console.log(response.data);
                    me.proveedores = response.data
                })
        },
        showDialog() {
            this.producto = {
                id: null,
                nombre: "",
                descripcion: "",
                proveedor:"",
                precio: ""
            };
            this.productoErrors = {
                nombre: false,
                descripcion: false,
                precio: false
            }
            $('#productoModal').modal('show');
        },
        showDialogEditar(producto) {
            let me = this;
            $('#productoModal').modal('show');
            me.editedProducto = me.productos.indexOf(producto);
            me.producto = Object.assign({}, producto);
        },
        hideDialog() {
            let me = this;
            setTimeout(() => {
                me.producto = {
                    id: null,
                    nombre: "",
                    descripcion: "",
                    precio: "",
                }
            }, 300);
            $('#productoModal').modal('hide');

        },
        async saveOrUpdate() {
            let me = this;
            me.producto.nombre == '' ? me.productoErrors.nombre = true : me.productoErrors.nombre = false;
            me.producto.descripcion == '' ? me.productoErrors.descripcion = true : me.productoErrors.descripcion = false;
            me.producto.precio == '' ? me.productoErrors.precio = true : me.productoErrors.precio = false;

            if (me.producto.nombre) {
                let accion = me.producto.id == null ? "add" : "upd";
                if (accion == "add") {
                    await this.axios.post('/producto', me.producto)
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
                    await this.axios.put(`/producto/${me.producto.id}`, me.producto)
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
        async eliminar(producto) {
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
                    me.editedProducto = me.productos.indexOf(producto);
                    this.axios.delete(`/producto/${producto.id}`)
                        .then(response => {
                            me.verificarAccion(null, response.status, "del");
                        }).catch(errors => {
                            console.log(errors);
                        })
                }
            })
        },
        verificarAccion(producto, statusCode, accion) {
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
                    me.productos.unshift(producto);
                    Toast.fire({
                        icon: 'success',
                        'title': 'Producto registrada con exito...!'
                    });
                    break;

                case "upd":
                    Object.assign(me.productos[me.editedProducto], producto);
                    Toast.fire({
                        icon: 'success',
                        'title': 'Producto actualizado con exito...!'
                    });
                    break;
                case "del":
                    if (statusCode == 200) {
                        me.productos.splice(this.editedProducto, 1);
                        Toast.fire({
                            icon: 'success',
                            'title': 'Producto eliminado con exito...!'
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
        this.fetchProductos();
        this.fetchProveedores();

    }
}
</script>
