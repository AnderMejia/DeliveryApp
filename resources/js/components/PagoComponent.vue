<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="float-start">Formas de Pago</h5>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table bordered">
                            <thead>
                                <tr>
                                    <th>Numero de tarjeta</th>
                                    <th>Nombre de Tarjeta</th>
                                    <th>Codigo</th>
                                    <th>Fecha Vencimiento</th>
                                    <th>Tipo Pago </th>
                                    <th>Usuario</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in pagos" :key="item.id">
                                    <td>{{ item.num_tajeta }}</td>
                                    <td>{{ item.nombre_tajeta }}</td>
                                    <td>{{ item.codigo }}</td>
                                    <td>{{ item.fecha_vencimiento }}</td>
                                    <td>{{ item.tipo_pago }}</td>
                                    <td>{{ item.user.name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--div class="modal fade" id="productoModal" tabindex="-1" aria-labelledby="productoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5" id="productoModalLabel">{{ formTitle }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    !--definiendo cuerpo del formulario local--
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="tipo">Tipo</label>
                            <select v-model="pago.tipoPago" class="form-control">
                                <option v-for="tipo in tipos" :value="tipoPago.value" >
                                {{ tipoPago.text }}
                                </option>
                            </select>
                            <span class="text-danger" v-show="autoErrors.tipo">Seleccione tipo</span>
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
    </div-->
</template>

<script>
export default {
    data() {
        return {
            pagos: [],
            pago: {
                id: null,
                numeroTarjeta: null,
                nombreTarjeta: "",
                codigo: null,
                fechaVencimiento: null,
                tipoPago: "",
                user_id: null,
                user: null,
            },
            editedPago: -1,
            pagoErrors: {
                numeroTarjeta: false,
                nombreTarjeta: false,
                codigo: false,
                fechaVencimiento: false,
                tipoPago: false,
                user: false,


            },
            users: []
        }
    },
    methods: {
        async fetchPagos() {
            let me = this;
            await this.axios.get('/pagos')
                .then(response => {
                    console.log(response.data);
                    me.pagos = response.data
                })
        },
        async fetchUsers() {
            let me = this;
            await this.axios.get('/api/users')
                .then(response => {
                    console.log(response.data);
                    me.users = response.data
                })
        }
    },
    mounted() {
        //console.log('Component mounted.');
        this.fetchUsers();
        this.fetchPagos();

    }
}
</script>
