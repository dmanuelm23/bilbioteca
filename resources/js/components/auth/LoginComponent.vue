<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Iniciar sesión</h3></div>
                    <div class="card-body">
                        <form>
                            <div class="form-floating mb-3">
                                <input class="form-control" id="email" type="email" placeholder="name@example.com"  v-model="login.email" />
                                <label for="email">Correo electrónico</label>
                            </div>
                            <small style="color:red;" v-for="(e_email, index) in errors.email" :key="index">{{e_email}}</small>
                            <div class="form-floating mb-3">
                                <input class="form-control" id="password" type="password" placeholder="Password" v-model="login.password" />
                                <label for="password">Password</label>
                            </div>
                            <small style="color:red;" v-for="(e_password, index) in errors.password" :key="index">{{e_password}}</small>
                            <small style="color:red;" v-for="(e_credential, index) in errors.credentials" :key="index">{{e_credential}}</small>
                            <div class="form-check mb-3">
                                <input class="form-check-input" id="remember" type="checkbox" value="" v-model="login.remember"/>
                                <label class="form-check-label" for="remember">Recuerda mi sesión</label>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                <button type="button" class="btn btn-primary" @click.prevent="enviar">Iniciar sesión</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center py-3">
                        <div class="small"><a href="/register">¿Necesitas una cuenta? !Registrate!</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    data() {
        return {
            errors:[],
            login:{
                email:'',
                password:'',
                remember:'',
            },
            invalid:{
                'email':0,
                'password':0,
                'credentials':0
            }
        }
    },
    methods: {
        enviar(){
            axios.post('login', this.login).then(response => {
                window.location.href = window.location.origin +'/home';
            })
            .catch((error)=> {
                console.log(error);
                if (error.response.status == 422) {
                    this.errors = error.response.data.errors;
                    this.showErrors();
                    console.log(this.errors);
                }
            });
        },
        showErrors(){
            for(let error in this.errors) {
                switch(error){
                    case 'email':
                        this.invalid.email = 1;
                        break;
                    case 'password':
                        this.invalid.password = 1;
                        break;
                }
            }

        }
    },
}
</script>
<style>
    .box{
        display:flex;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
    }
    .form-signin{
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: auto;
    }
</style>