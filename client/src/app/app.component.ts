import { Component, OnInit, AfterViewInit } from '@angular/core';
import { MatTableDataSource } from '@angular/material/table';
import { OrderInterface } from 'src/interface/OrderInterface';
import { OrderService } from './service/order.service';
import { CustomResponse } from 'src/interface/CustomResponse';
import { MatSnackBar } from '@angular/material/snack-bar';
import { HttpErrorResponse } from '@angular/common/http';
import * as introJs from 'intro.js/intro.js';
import { MatDialog } from '@angular/material/dialog';
import { AddOrderComponent } from './dialog/add-order/add-order.component';


@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit, AfterViewInit {
  title = 'client';
  displayedColumns: string[] = ['user_name', 'product_name', 'quantity', 'total', 'created_at', 'actions'];
  datasource = new MatTableDataSource<OrderInterface>();

  listType = 'user';
  searchName = '';

  durationList = [{ key: 1, value: 'Today' }, { key: 7, value: '7 days' }, { key: null, value: 'All days' }];
  listTypes = [{ key: 'user', value: 'User' }, { key: 'product', value: 'Product' }];

  constructor(private orderService: OrderService, private matSnackBar: MatSnackBar, public dialog: MatDialog) {

  }

  ngOnInit(): void {
    this.listOrders();
  }
  ngAfterViewInit(): void {
    // introJs().start();
  }

  addOrder() {
    const ref = this.dialog.open(AddOrderComponent, {
      width: '350px'
    });
  }

  listOrders() {
    this.orderService.listOrders(this.listType, this.searchName)
      .then((data: CustomResponse<OrderInterface>) => {
        console.log(data.body, 'response');
        this.datasource.data = data.body;
      })
      .catch(error => {
        console.log('[listOrders]', error);
        let message = 'Error occured';
        if (error instanceof HttpErrorResponse) {
          message = error.message;
        }
        this.matSnackBar.open(message, null, { duration: 3000 });
      });
  }



}
