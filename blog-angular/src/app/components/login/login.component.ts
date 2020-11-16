import { Component, OnInit } from '@angular/core';
import { User } from '../../models/user';
import { UserService } from '../../services/user.service';
import { Router, ActivatedRoute, Params } from '@angular/router';


@Component({
  selector: 'login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
  providers: [UserService]
})
export class LoginComponent implements OnInit {
  public page_title: string;
  public user: User;
  public status: string;
  public token;
  public identity;
  public errorMessage: string;
  public successMessage: string;

  constructor(private _userService:UserService, private _router: Router, private _route: ActivatedRoute ) { 
    this.page_title = 'Identificate';
    this.user = new User(1, '', '', 'ROLE_USER', '', '' , '', '');  
  }

  ngOnInit(): void {
    // Se ejecuta iempre que se cargue el componente y se carga cuando 
    // llegue sure en la url
    this.logOut();
  }

  onSubmit(form){
    this._userService.login(this.user).subscribe(
      response => {
        //devuelve token
        if(response.status != 'Error'){
          this.status = 'Success';
          this.token = response;

          //Objeto de usuario identificado
          this._userService.login(this.user, true).subscribe(
            response => {
              this.identity = response;
              //Almacenamiendo de datos de usuario identificado
              console.log(this.token);
              console.log(this.identity);
              localStorage.setItem('token', this.token);
              localStorage.setItem('identity', JSON.stringify(this.identity));
              
              this._router.navigate(['inicio']);
            }, 
            error => {
              this.status = 'Error'
              console.log(<any>error)
            }
          )

        }else{
          this.status = 'Error';
          this.errorMessage = 'Intento de inicio de sesion incorrecto';
        }
      }, 
      error => {
        this.status = 'Error' 
        console.log(<any>error)
      }
    )
  }

  logOut(){
    this._route.params.subscribe(params=>{
        let logout = +params['sure'];

        if(logout == 1){
          localStorage.removeItem('identity');
          localStorage.removeItem('token');
          
          this.identity = null;
          this.token = null;

          // Redireccion a inicio
          this._router.navigate(['inicio']);

        }
    });
  }
}
