<template>
    <div class="container-fluid px-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Prestamos</li>
            </ol>
        </nav>
        <div v-if="openMessage">
            <Notification :message="message" />
        </div>
        <br>
        <div class="row">
            <div class="col-12">
                <h2>Lista de libros disponibles</h2>
                <table class="table table-hover" id="books">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Titulo</th>
                            <th>Autor</th>
                            <th>Publicación</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="book in books" :key="book.id">
                            <td>{{book.id}}</td>
                            <td>{{book.title}}</td>
                            <td>{{book.author}}</td>
                            <td>{{book.publication_date}}</td>
                            <td>{{book.estatus}}</td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-outline-primary btn-sm"
                                    @click.prevent="openModalLoan(book)"            
                                >
                                    Prestar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_loan_book" tabindex="-1" role="dialog" aria-labelledby="loanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="loanModalLabel">Prestar libro</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" @click.prevent="closeModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <p>¿Deseas prestar el libro “{{book}}”?</p>
                </div>
                <div class="modal-footer">
                <button @click.prevent="closeModal()" type="button" class="btn btn-secondary" data-dismiss="modal">
                    Cancelar
                </button>
                <button @click="loanBook()" type="button" class="btn btn-primary">
                    Prestar
                </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
//Bootstrap and jQuery libraries
import "bootstrap/dist/css/bootstrap.min.css";
import "jquery/dist/jquery.min.js";
//Datatable Modules
import "datatables.net-dt/js/dataTables.dataTables";
import "datatables.net-dt/css/jquery.dataTables.min.css";
import "datatables.net-buttons/js/dataTables.buttons.js";
import "datatables.net-buttons/js/buttons.colVis.js";
import "datatables.net-buttons/js/buttons.flash.js";
import "datatables.net-buttons/js/buttons.html5.js";
import "datatables.net-buttons/js/buttons.print.js";
import JSZip from "jszip";
import pdfMake from "pdfmake/build/pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";
pdfMake.vfs = pdfFonts.pdfMake.vfs;
window.JSZip = JSZip;
import $ from "jquery";
import Notification from "./NotificationComponent";
export default {
    inject: ["datatablesConfig"],
    components:{
        Notification
    },
    data() {
        return {
            timeDestroy:'',
            openMessage:0,
            message:"",
            book_id:'',
            book:'',
            books:[],
        }
    },
    methods: {
        loanBook() {
            axios.post('loan-books/'+this.book_id).then(response=>{
                if(response && response.data.statusCode ==200){
                    this.loanBookModal.hide();
                    this.getBooks();
                    this.openMessage=1;
                    this.message = response.data.message;
                    this.timeDestroy = setTimeout(()=>{
                        this.openMessage=0;
                    },4000);
                }
            })
        },
        closeModal() {
            this.loanBookModal.hide();
        },
        openModalLoan(data) {
            this.book_id = data.id;
            this.book = data.title;
            this.loanBookModal.show();
        },
        getBooks(){
            axios.get('list-available-books').then(response=>{
                this.books = response.data.books;
            })
        }
    },
    mounted() {
        this.getBooks();
        this.loanBookModal = new bootstrap.Modal(
            document.getElementById("modal_loan_book")
        );
    },
    created() {
        let self = this;
        this.$nextTick(() => {
        self.dataTable = $("#books").DataTable(self.datatablesConfig);
        });
    },
    watch:{
        books() {
            let self = this;
            if (self.dataTable) {
                self.dataTable.destroy();
            }
            this.$nextTick(() => {
                self.dataTable = $("#books").DataTable(self.datatablesConfig);
            });
        },
    }
}
</script>