import { Component, OnInit } from '@angular/core';
import { User } from '../../models/user';
import { UserService } from '../../services/user.service';


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

  constructor(private _userService:UserService) { 
    this.page_title = 'Identificate';
    this.user = new User(1, '', '', 'ROLE_USER', '', '' , '', '');  
  }

  ngOnInit(): void {
  }

  onSubmit(form){
    this._userService.login(this.user).subscribe(
      response => {
        //devuelve token
        if(status != 'Error'){
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
            }, 
            error => {
              this.status = 'Error'
              console.log(<any>error)
            }
          )

        }else{
          this.status = 'Error';
        }
      }, 
      error => {
        this.status = 'Error'
        console.log(<any>error)
      }
    )
  }
}
