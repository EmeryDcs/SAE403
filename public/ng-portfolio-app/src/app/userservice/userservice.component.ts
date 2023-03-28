import { Component,OnInit  } from '@angular/core';
import { UserService  } from '../user.service';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-userservice',
  templateUrl: './userservice.component.html',
  styleUrls: ['./userservice.component.css']
})
export class UserserviceComponent implements OnInit{
  users: any[] = [];

  constructor(private userService: UserService,private http: HttpClient) { }

  // ngOnInit() {
  //   this.userService.etudiant().subscribe((data: any[]) => {
  //     this.users = data;
  //   });
  // }

  ngOnInit() {
        this.http.get<any[]>('http://localhost/SAE403/public/index.php/api/etudiant').subscribe(data => {
          this.users = data;
        });
        console.log('test')
  }


 
}

