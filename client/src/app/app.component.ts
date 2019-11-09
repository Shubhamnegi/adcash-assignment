import { Component, OnInit, AfterViewInit, ViewChild } from '@angular/core';
import { MatTableDataSource } from '@angular/material/table';
import { OrderInterface } from 'src/interface/OrderInterface';
import { OrderService } from './service/order.service';
import { CustomResponse } from 'src/interface/CustomResponse';
import { MatSnackBar } from '@angular/material/snack-bar';
import { HttpErrorResponse } from '@angular/common/http';
import * as introJs from 'intro.js/intro.js';
import { MatDialog } from '@angular/material/dialog';
import { AddOrderComponent } from './dialog/add-order/add-order.component';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { MatPaginator } from '@angular/material/paginator';


@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit, AfterViewInit {
  title = 'client';
  displayedColumns: string[] = ['user_name', 'product_name', 'quantity', 'total', 'created_at', 'actions'];
  datasource = new MatTableDataSource<OrderInterface>();



  durationList = [{ key: 1, value: 'Today' }, { key: 7, value: '7 days' }, { key: 0, value: 'All days' }];
  listTypes = [{ key: 'user', value: 'User' }, { key: 'product', value: 'Product' }];

  currentSearchOptions = {
    duration: 1,
    getby: 'user',
    name: ''
  };

  totalRecords = null;

  limit = 10;
  skip = 0;

  searchForm: FormGroup;
  @ViewChild(MatPaginator, { static: true }) paginator: MatPaginator;


  constructor(private orderService: OrderService, private matSnackBar: MatSnackBar, public dialog: MatDialog) {
    this.searchForm = new FormGroup({
      duration: new FormControl(this.currentSearchOptions.duration, [Validators.required]),
      getby: new FormControl(this.currentSearchOptions.getby, [Validators.required]),
      name: new FormControl(this.currentSearchOptions.name)
    });
  }


  ngOnInit(): void {
    this.listOrders();
    this.paginator.page.subscribe((event) => {
      console.log("caught page event");
      this.limit = event.pageSize;
      this.skip = event.pageSize * (event.pageIndex);
      this.listOrders();
    })
  }
  ngAfterViewInit(): void {
    introJs().start();
  }

  submitSearch() {
    this.currentSearchOptions = this.searchForm.value;
    this.listOrders();
  }

  resetSearch() {
    this.skip = 0;
    this.currentSearchOptions = {
      duration: 1,
      getby: 'user',
      name: ''
    };
    this.searchForm.patchValue(this.currentSearchOptions);
    this.paginator.page.emit({
      length: this.totalRecords,
      pageIndex: 0,
      pageSize: this.limit,
      previousPageIndex: 1
    })
  }

  editOrder(id) {
    this.openDialog('edit', id);
  }
  addOrder() {
    this.openDialog('create');
  }

  openDialog(type, id = null) {
    const ref = this.dialog.open(AddOrderComponent, {
      width: '350px',
      data: {
        type: type,
        id: id
      }
    });
    ref.afterClosed().subscribe(result => {
      console.log('result on close', result);
      if (result) {
        this.resetSearch();
      }
    });
  }

  listOrders() {
    this.orderService.listOrders(this.currentSearchOptions.getby, this.currentSearchOptions.name, this.limit, this.skip)
      .then((data: CustomResponse<OrderInterface[]>) => {
        console.log(data.body, 'response');
        this.datasource.data = data.body;
        this.totalRecords = data.count;
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
