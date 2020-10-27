//Imports Necesarios
import { ModuleWithProviders } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

//Importar Componentes
import { LoginComponent } from './components/login/login.component';
import { RegisterComponent} from './components/register/register.component';
import { HomeComponent } from './components/home/home.component';
import { ErrorComponent } from './components/error/error.component';

//Creacion de rutas
const appRoutes: Routes = [
    {path: '', component: HomeComponent},
    {path: 'inicio', component:HomeComponent},
    {path: 'login', component:LoginComponent},
    {path: 'register', component:RegisterComponent},
    {path: '**', component:ErrorComponent}
];

//Exportar configuracion
    //Cargar router como servicio
    export const appRoutingProviders: any[] = [];
    //Modulo del router
    export const routing: ModuleWithProviders = RouterModule.forRoot(appRoutes);