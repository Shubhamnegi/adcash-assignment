import { Component, OnInit, Inject } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { ProductService } from 'src/app/service/product.service';
import { UserService } from 'src/app/service/user.service';
import { MatSnackBar } from '@angular/material/snack-bar';
import { CustomResponse } from 'src/interface/CustomResponse';
import { UserInterface } from '../../../interface/UserInterface';
import { ProductInterface } from 'src/interface/ProductInterface';

@Component({
  selector: 'app-add-order',
  templateUrl: './add-order.component.html',
  styleUrls: ['./add-order.component.scss']
})
export class AddOrderComponent implements OnInit {

  users: UserInterface[] = [];
  products: ProductInterface[] = [];
  constructor(public dialogRef: MatDialogRef<AddOrderComponent>,
    @Inject(MAT_DIALOG_DATA) public data: any, private productService: ProductService,
    private userService: UserService, private matSnackBar: MatSnackBar) { }

  ngOnInit() {
    this.getUsersAndProducts();
  }

  getUsersAndProducts() {
    Promise.all([
      this.userService.listUsers(),
      this.productService.listProducts()
    ])
      .then((data: any[]) => {
        console.log(data, 'Response for user and product list');
        const userResp: CustomResponse<UserInterface> = data[0];
        const productResp: CustomResponse<ProductInterface> = data[1];
        this.users = userResp.body;
        this.products = productResp.body;

      })
      .catch(error => {
        console.log('getUsersAndProducts', error);
        this.matSnackBar.open('Error fetching form data');
      });
  }

}
