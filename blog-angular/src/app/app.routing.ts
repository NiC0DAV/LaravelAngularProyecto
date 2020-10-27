//Imports Necesarios
import { ModuleWithProviders } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

//Importar Componentes
import { LoginComponent } from './components/login/login.component';
import { RegisterComponent} from './components/register/register.component';

//Creacion de rutas
const appRoutes: Routes = [
    {path: '', component: LoginComponent},
    {path: 'inicio', component:LoginComponent},
    {path: 'login', component:LoginComponent},
    {path: 'register', component:RegisterComponent}
];

//Exportar configuracion
    //Cargar router como servicio
    export const appRoutingProviders: any[] = [];
    //Modulo del router
    export const routing: ModuleWithProviders = RouterModule.forRoot(appRoutes);