// import { Component } from '@angular/core';
// import { HttpClient } from '@angular/common/http';

// @Component({
//   selector: 'app-user-list',
//   template: `
//     <ul>
//       <li *ngFor="let user of users">{{ user.prenom }}</li>
//     </ul>
//   `
// })
// export class UserListComponent {
//   customers: any[];

//   constructor(private http: HttpClient) {}

//   ngOnInit() {
//     this.http.get<any[]>('/api/customers').subscribe(data => {
//       this.customers = data;
//     });
//   }
// }