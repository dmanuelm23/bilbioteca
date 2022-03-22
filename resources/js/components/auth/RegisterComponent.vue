<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Crear cuenta</h3></div>
                    <div class="card-body">
                        <form>
                            <div class="form-floating mb-3">
                                <input class="form-control" id="name" name="name" type="name" placeholder="name@example.com" v-model="register.name" />
                                <label for="name">Nombre</label>
                            </div>
                            <small style="color:red;" v-for="(e_name, index) in errors.name" :key="index">{{e_name}}</small>
                            <div class="form-floating mb-3">
                                <input class="form-control" id="email" name="email" type="email" placeholder="name@example.com" v-model="register.email" />
                                <label for="email">Correo electrónico</label>
                            </div>
                            <small style="color:red;" v-for="(e_email, index) in errors.email" :key="index">{{e_email}}</small>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <input class="form-control" id="password" type="password" placeholder="Crear una contraseña" v-model="register.password" />
                                        <label for="password">Password</label>
                                    </div>
                                    <small style="color:red;" v-for="(e_password, index) in errors.password" :key="index">{{e_password}}</small>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirmar contraseña" v-model="register.password_confirmation"/>
                                        <label for="password_confirmation">Confirm Password</label>
                                    </div>
                                    <small style="color:red;" v-for="(e_password_confirmation, index) in errors.password_confirmation" :key="index">{{e_password_confirmation}}</small>
                                </div>
                            </div>
                            <div class="mt-4 mb-0">
                                <div class="d-grid"><button type="button" class="btn btn-primary btn-block" href="#" @click.prevent="enviar">Registrarse</button></div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center py-3">
                        <div class="small"><a href="/login">¿Tienes una cuenta? Iniciar sesión</a></div>
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
            register:{
                name:'',
                email:'',
                password:'',
                password_confirmation:'',
            },
            invalid:{
                'name':0,
                'email':0,
                'password':0,
                'password_confirmation':0
            }
        }
    },
    methods: {
        enviar(){
            axios.post('register', this.register).then(response => {
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
                    case 'name':
                        this.invalid.name = 1;
                        break;
                    case 'email':
                        this.invalid.email = 1;
                        break;
                    case 'password':
                        this.invalid.password_confirmation = 1;
                        break;
                    case 'password_confirmation':
                        this.invalid.password_confirmation = 1;
                        break;
                }
            }

        }
        
    },
    
}
</script>