import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { UserserviceComponent } from './userservice/userservice.component';

const routes: Routes = [
  {path:'test', component:  UserserviceComponent  }  
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
