<template>
    <div class="container-fluid px-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Libros</li>
            </ol>
        </nav>
        <div v-if="openMessage">
            <Notification :message="message" />
        </div>
        <div class="card" v-if="formOpen">
            <h5 class="card-header">Libro</h5>
            <div class="card-body">
                <form class="row g-3">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'error':invalid.title}">
                            <label for="title" class="form-label">Título</label>
                            <input 
                                type="text"
                                class="form-control"
                                required
                                id="title"
                                name="title"
                                placeholder="Nombre libro ejemplo: Harry Potter"
                                @click="validateAlerts(1)"
                                :class="{'form-alert':invalid.title}"
                                v-model="dataBook.title"
                            >
                            <small class="message" v-for="(e_title, index) in errors.title" :key="index">{{e_title}}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'error':invalid.author}">
                            <label for="author" class="form-label">Autor</label>
                            <input
                                type="text"
                                class="form-control"
                                required
                                id="author"
                                name="author"
                                placeholder="Autor libro ejemplo: Luis Manuel"
                                @click="validateAlerts(2)"
                                :class="{'form-alert':invalid.author}"
                                v-model="dataBook.author"
                            >
                            <small class="message" v-for="(e_author, index) in errors.author" :key="index">{{e_author}}</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group" :class="{'error':invalid.publication_date}">
                            <label for="publication_date" class="form-label">Fecha de publicación</label>
                            <input 
                                required
                                type="date"
                                class="form-control"
                                id="publication_date"
                                @click="validateAlerts(3)"
                                :class="{'form-alert':invalid.publication_date}"
                                v-model="dataBook.publication_date"
                            >
                            <small class="message" v-for="(e_publication_date, index) in errors.publication_date" :key="index">{{e_publication_date}}</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <button v-if="option=='guardar'" type="button" class="btn btn-primary" @click.prevent="save()">Guardar</button>&nbsp;
                        <button v-if="option=='editar'" type="button" class="btn btn-primary" @click.prevent="edit()">Modificar</button>&nbsp;
                        <button type="button" class="btn btn-secondary" @click.prevent="closeForm()">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12">
                <h2>Lista de libros</h2>
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
                                <div class="btn-group">
                                    <button 
                                        type="button"
                                        class="btn btn-outline-primary btn-sm "
                                        @click.prevent="openDetailsModal(book)"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="Detalles"
                                    >
                                       <i class="fas fa-info-circle"></i>
                                    </button>
                                    <button 
                                        type="button"
                                        class="btn btn-outline-primary btn-sm"
                                        @click.prevent="editRecord(book)"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="Editar"
                                    >
                                        <i class="far fa-edit fa-sm"></i>
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary btn-sm"
                                        @click.prevent="deleteRecord(book)"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="Eliminar"
                                    >
                                        <i class="far fa-trash-alt fa-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Eliminar libro</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" @click.prevent="closeModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <p>¿Deseas eliminar el libro “{{book}}”?</p>
                </div>
                <div class="modal-footer">
                <button @click.prevent="closeModal()" type="button" class="btn btn-secondary" data-dismiss="modal">
                    Cancelar
                </button>
                <button @click="deleteBook()" type="button" class="btn btn-danger">
                    Eliminar
                </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_detail" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Información del libro</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" @click.prevent="closeDetailModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <label for="titleInfo"><strong>Titulo:</strong> {{title}}</label><br>
                    <label for="AutorInfo"><strong>Autor: </strong>  {{author}}</label><br>
                    <label for="publicationDateInfo"><strong>Publicado:</strong>  {{publication_date}}</label>
                    <hr>
                    <div v-if="userBook.length>0">
                        <h5>Libro prestado a:</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Correo eléctronico</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user in userBook" :key="user.id">
                                        <td>{{user.name}}</td>
                                        <td>{{user.email}}</td>
                                    </tr>
                                </tbody>
                            </table>
                    </div>
                    <div v-else>
                        <h5>Libro disponible</h5>
                    </div>
                    
                </div>
                <div class="modal-footer">
                <button @click.prevent="closeDetailModal()" type="button" class="btn btn-primary" data-dismiss="modal">
                    Aceptar
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
            formOpen:0,
            option:"guardar",
            errors:[],
            books:[],
            invalid:{
				'title':0,
                'author':0,
                'publication_date':0,
            },
            dataBook:{
                title:'',
                author:'',
                publication_date:'',
                status:'',
            },
            title:'',
            author:'',
            publication_date:'',
            userBook:[],
        }
    },
    methods: {
        openDetailsModal(book){
            this.title=book.title;
            this.author=book.author;
            this.publication_date=book.publication_date;
            this.userBook = book.books_users;
            this.bookDetailModal.show();
        },
        closeDetailModal(){
            this.bookDetailModal.hide();
        },
        deleteBook() {
            axios.delete('books/'+this.book_id).then(response=>{
                if(response && response.data.statusCode ==200){
                    this.bookDeleteModal.hide();
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
            this.bookDeleteModal.hide();
        },
        edit(){
            axios.put('books/'+ this.book_id, this.dataBook).then(response=>{
                if(response && response.data.statusCode ==200){
                    this.formOpen = 0;
                    this.getBooks();
                    this.openMessage=1;
                    this.message = response.data.message;
                    this.timeDestroy = setTimeout(()=>{
                        this.cleanForm();
                        this.openMessage=0;
                    },4000);
                }
            })
            .catch((error)=>{
                if(error.response.status==422){
                    this.errors = error.response.data.errors;
                    this.showErrors();
                }
                else{
                    console.log(this.errors);
                }
            })
        },
        resetErrorMessages(){
            for(let inval in this.invalid){
                this.invalid[inval]=0;
            }
        },
        showErrors(){
            this.resetErrorMessages()
            for(let error in this.errors){
                switch(error){
                    case 'title':
                        this.invalid.title= 1;
                    break;
                    case 'author':
                        this.invalid.author= 1;
                    break;
                    case 'publication_date':
                        this.invalid.publication_date= 1;
                    break;
                }
            }
        },
        save(){
            axios.post('books', this.dataBook).then(response=>{
                if(response && response.data.statusCode ==200){
                    this.formOpen = 0;
                    this.closeForm();
                    this.getBooks();
                    this.openMessage=1;
                    this.message = response.data.message;
                    this.timeDestroy = setTimeout(()=>{
                        this.openMessage=0;
                    },4000);
                }
            })
            .catch((error)=>{
                if(error.response.status==422){
                    this.errors = error.response.data.errors;
                    this.showErrors();
                }
                else{
                    console.log(this.errors);
                }
            })
        },
        closeForm(){
            this.formOpen = 0;
            this.option = "guardar";
            this.cleanForm();
        },
        validateAlerts(value){
            switch (value) {
                case 1:
                    this.errors.title = "";
                    this.invalid.title = 0;
                    break;
                case 2:
                    this.errors.author = "";
                    this.invalid.author = 0;
                    break;
                case 3:
                    this.errors.publication_date = "";
                    this.invalid.publication_date = 0;
                    break;
            }
        },
        cleanForm(){
            this.dataBook.title="";
            this.dataBook.author="";
            this.dataBook.publication_date="";
        },
        newRecord(){
            this.formOpen = 1;
            this.option = "guardar";
            this.cleanForm();
        },
        editRecord(book){
            this.formOpen = 1;
            this.option = "editar";
            this.cleanForm();

            this.book_id=book.id;
            this.dataBook.title=book.title;
            this.dataBook.author=book.author;
            this.dataBook.publication_date=book.publication_date;
        },
        deleteRecord(data) {
            this.book_id = data.id;
            this.book = data.title;
            this.bookDeleteModal.show();
        },
        getBooks(){
            axios.get('list-books').then(response=>{

                this.books = response.data.books;
                console.log(this.books);
            })
        }
    },
    mounted() {
        this.getBooks();
        this.bookDeleteModal = new bootstrap.Modal(
            document.getElementById("modal_delete")
        );

        this.bookDetailModal = new bootstrap.Modal(
            document.getElementById("modal_detail")
        );

        var tooltipTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="tooltip"]')
        );
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    },
    created() {
        let self = this;
        this.$nextTick(() => {
        self.dataTable = $("#books").DataTable(self.datatablesConfig);
        new $.fn.dataTable.Buttons(self.dataTable, {
            name: "otherButton",
            buttons: [
            {
                className: "btn btn-outline-primary btn-sm",
                text: '<i class="fas fa-plus"></i>',
                titleAttr: "Agregar",
                exportOptions: {
                columns: ":visible",
                },
                action: function (e, dt, node, config) {
                self.newRecord(e.target.dataset.itemId);
                },
            },
            ],
        });
        self.dataTable
            .buttons("otherButton", null)
            .containers()
            .addClass("groupleft")
            .insertBefore(".dataTables_filter");
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
                new $.fn.dataTable.Buttons(self.dataTable, {
                name: "otherButton",
                buttons: [
                    {
                    className: "btn btn-outline-primary btn-sm",
                    text: '<i class="fas fa-plus"></i>',
                    titleAttr: "Agregar",
                    exportOptions: {
                        columns: ":visible",
                    },
                    action: function (e, dt, node, config) {
                        self.newRecord(e.target.dataset.itemId);
                    },
                    },
                ],
                });
                self.dataTable
                .buttons("otherButton", null)
                .containers()
                .addClass("groupright")
                .insertBefore(".dataTables_filter");
            });
        },
    }
}
</script>

<style >
    .form-group.error .form-control{
	    border-color: #FF6868;
    }
    .form-group.error .message,
    .form-group.normal .message{
	    display: block;
    }
    .form-group.error .message,
    .form-group.error .message a{
	    color: #FF6868;
    }
</style>