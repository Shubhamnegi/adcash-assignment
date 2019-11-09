import { Component, OnInit, Inject } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { ProductService } from 'src/app/service/product.service';
import { UserService } from 'src/app/service/user.service';
import { MatSnackBar } from '@angular/material/snack-bar';
import { CustomResponse } from 'src/interface/CustomResponse';
import { UserInterface } from '../../../interface/UserInterface';
import { ProductInterface } from 'src/interface/ProductInterface';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { OrderService } from 'src/app/service/order.service';

@Component({
  selector: 'app-add-order',
  templateUrl: './add-order.component.html',
  styleUrls: ['./add-order.component.scss']
})
export class AddOrderComponent implements OnInit {
  type = 'create';
  id = null;
  users: UserInterface[] = [];
  products: ProductInterface[] = [];

  orderForm: FormGroup = null;
  constructor(public dialogRef: MatDialogRef<AddOrderComponent>,
    @Inject(MAT_DIALOG_DATA) public data: { type: string, id: number },
    private orderService: OrderService, private productService: ProductService,
    private userService: UserService, private matSnackBar: MatSnackBar) {
    
    this.type = data.type;
    this.id = data.id;

    this.orderForm = new FormGroup({
      userId: new FormControl('', [Validators.required]),
      productId: new FormControl('', [Validators.required]),
      quantity: new FormControl(0, [Validators.required, Validators.min(0), Validators.max(100)])
    });
  }

  ngOnInit() {
    this.getUsersAndProducts();
  }

  submit() {
    const values = this.orderForm.value;
    console.log(values, 'values');
    this.orderService.createOrder(values)
      .then((data: CustomResponse<boolean>) => {
        this.dialogRef.close(true);
      })
      .catch((error) => {
        console.log(error, '[submit]');
        this.matSnackBar.open('Error occured', null, { duration: 3000 });
      });
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
        this.matSnackBar.open('Error fetching form data', null, { duration: 3000 });
      });
  }

}
