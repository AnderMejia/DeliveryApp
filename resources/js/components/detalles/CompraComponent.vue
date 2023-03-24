<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col">
                <button class="btn btn-outline-primary" @click="showDiv = !showDiv">{{ btnReservTitle }}</button>
            </div>
            <div class="container-fluid">
                <div id="show">
                    <div v-show="showDiv">
                        <h4>Detalle de la Compra</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Descripcion</th>
                                    <th>Proveedor</th>
                                    <th>Cliente</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in compraForm.detalleCompra" :key="index">
                                    <td>{{ item.producto.nombre }}</td>
                                    <td>{{ item.producto.descripcion }}</td>
                                    <td>{{ item.producto.proveedor.nombre }}</td>
                                    <td>{{ user.name }}</td>
                                    <td>{{ item.producto.precio }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" @click="removeItem(index)">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-left ">Total Alquiler</td>
                                    <td>{{ total }}</td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <button class="btn btn-primary" @click="saveReserva"
                                            :disabled="compraForm.detalleCompra.length < 1">Confirmar Reserva</button>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4" v-for="(item, index) in productos" :key="index">
                        <div class="card">
                            <img src="https://picsum.photos/300/300" class="card-img-top" alt="">
                            <div class="card-body">
                                <h5 class="card-title text-bold"><b>{{ item.nombre }}</b>
                                    <hr>&nbsp;{{ item.proveedor.nombre }}
                                </h5>
                                <p class="card-text">Precio : <b class="text-warning">${{ item.precio }}</b></p>
                                <a href="#" class="btn btn-primary" @click="addToReserva(item)">Comprar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    props: ['user'],
    data() {
        return {
            productos: [],
            compraForm: {
                id: null,
                nombre: "",
                descripcion: "",
                proveedor_id: null,
                proveedor:null,
                precio: 0,
                user: null,
                detalleCompra: []
            },
            showDiv: false,
        }
    },
    computed: {
        total() {
            var totalCompra = 0;
            this.compraForm.detalleCompra.forEach(element => {
                totalCompra += (element.producto.precio +  this.productos.precio)
            })
            this.compraForm.precio = totalCompra;
            return totalCompra;
        /*return this.compraForm.detalleCompra.reduce((total, item) => {
      return total + item.producto.precio;
    }, 0);*/
        },
        btnReservTitle() {
            return this.showDiv == false ? "Ver Compra" : "Ocultar Compra"
        },
    },
    methods: {
        async fetchProductos() {
            let me = this;
            await this.axios.get('/productos-compra')
                .then(response => {
                    console.log(response.data);
                    me.productos = response.data
                })
        },
        async fetchCompras() {
            let me = this;
            await this.axios.get('/compras')
                .then(response => {
                    console.log(response.data);
                    me.compras = response.data
                })
        },
        removeItem(i) {
            this.compraForm.detalleCompra.splice(i, 1);

        },
        addToReserva(item, index) {
            let me = this;
            me.compraForm.detalleCompra.push({
                id: null,
                producto: item,
            });
            console.log(me.compraForm.detalleCompra)
        },
        showDialog() {
            this.producto = {
                id: null,
                nombre: "",
                descripcion: "",
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

        }
    },
    mounted() {
        console.log(this.user);
        this.fetchProductos();
        this.fetchCompras();

    }
}
</script>
