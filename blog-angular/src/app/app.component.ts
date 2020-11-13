import { Component, OnInit, DoCheck } from '@angular/core';//Cargar siempre oninit y docheck para actualizar varaibles constantemente
import { UserService } from './services/user.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
  providers: [UserService]
})
export class AppComponent implements OnInit, DoCheck {
  title = 'Blog de Angular';
  public token;
  public identity;
  constructor(public _userService: UserService){
    this.loadUser();
  }
  ngOnInit(){
    console.log("WebApp cargada Succesfully");
  }

  ngDoCheck(){
    this.loadUser();
  }

  loadUser(){
    this.identity = this._userService.getIdentity();
    this.token = this._userService.getToken();
  }

}
