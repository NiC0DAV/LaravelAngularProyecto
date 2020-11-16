import { Component, OnInit } from '@angular/core';
import { User } from '../../models/user';
import { UserService } from '../../services/user.service';
import { global } from '../../services/global';

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

  public froala_options: Object = {
    charCounterCount: true,
    toolbarButtons: ['bold', 'italic', 'underline', 'paragraphFormat','alert','emoticonsSet'],
    toolbarButtonsXS: ['bold', 'italic', 'underline', 'paragraphFormat','alert','emoticonsSet'],
    toolbarButtonsSM: ['bold', 'italic', 'underline', 'paragraphFormat','alert','emoticonsSet'],
    toolbarButtonsMD: ['bold', 'italic', 'underline', 'paragraphFormat','alert','emoticonsSet'],
  };

  public afuConfig = {
    multiple: false,
    formatsAllowed: ".jpg, .png, .gif",
    maxSize: "250",
    uploadAPI:  {
      url: global.url+'user/upload',
      headers: {
        "Authorization": this._userService.getToken()
      },
    },
    theme: "attachPin",
    hideProgressBar: false,
    hideResetBtn: true,
    hideSelectBtn: false,
    attachPinText: 'Sube tu avatar'
  };

  public url;


  constructor(private _userService: UserService) { 
    this.page_title = 'Ajustes de Usuario';
    this.user = new User(1, '', '', 'ROLE_USER', '', '' , '', ''); 
    this.identity = this._userService.getIdentity();
    this.token = this._userService.getToken();
    this.url = global.url;
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

  avatarUpload(datos){
    let data = JSON.parse(datos.response);
    console.log(data);
    this.user.image = data.image;
  }

}
