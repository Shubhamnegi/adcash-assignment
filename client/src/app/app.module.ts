import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { MatSharedModule } from './mat-shared/mat-shared.module';
import { HttpClientModule } from '@angular/common/http';
import { AddOrderComponent } from './dialog/add-order/add-order.component';

@NgModule({
  declarations: [
    AppComponent,
    AddOrderComponent
  ],
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
    MatSharedModule,
    HttpClientModule
  ],
  entryComponents: [AddOrderComponent],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
