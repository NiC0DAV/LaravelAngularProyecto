import { Component, OnInit } from '@angular/core';
import { User } from '../../models/user';
import { UserService } from '../../services/user.service';

@Component({
  selector: 'app-user-edit',
  templateUrl: './user-edit.component.html',
  styleUrls: ['./user-edit.component.css'],
  providers: [UserService]
})
export class UserEditComponent implements OnInit {
  public page_title: string;
  public user: User;
  public identity;
  public token;
  public status;
  constructor(private _userService: UserService) { 
    this.page_title = 'Ajustes de Usuario';
    this.user = new User(1, '', '', 'ROLE_USER', '', '' , '', ''); 
    this.identity = this._userService.getIdentity();
    this.token = this._userService.getToken();
    // Llenar objeto usuario
    this.user = new User(this.identity.sub, 
                        this.identity.name, 
                        this.identity.surname, 
                        'ROLE_USER',
                        this.identity.email,
                        this.identity.password,
                        this.identity.description,
                        this.identity.image);
    
  }

  ngOnInit(): void {
  }

  onSubmit(form){
    this._userService.update(this.token,this.user).subscribe(
      response => {
        if(response && response['status'] == 'Success'){
          this.status = 'Success';

          if(response['changes']['name']){
            this.user.name = response['changes']['name'];
          }
          if(response['changes']['surname']){
            this.user.surname = response['changes']['surname'];
          }
          if(response['changes']['email']){
            this.user.email = response['changes']['email'];
          }          
          if(response['changes']['description']){
            this.user.description = response['changes']['description'];
          }
          if(response['changes']['description']){
            this.user.image = response['changes']['description'];
          }

          this.identity = this.user;
          //Actualizar usuario en sesion
          localStorage.setItem('identity', JSON.stringify(this.identity));
        }else{
          this.status = 'Error';
        }

      },
      error => {
        this.status = 'Error'
        console.log(<any>error)
      }
    );
  }

}
