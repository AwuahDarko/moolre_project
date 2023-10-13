import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
// import { DemoComponent } from './demo/demo.component';
import { Injector } from '@angular/core';
import {createCustomElement} from '@angular/elements';
import { CheckoutUiComponent } from './checkout-ui/checkout-ui.component';
import { MomoUiComponent } from './momo-ui/momo-ui.component';
import { CardUiComponent } from './card-ui/card-ui.component';
import { ModalUiComponent } from './modal-ui/modal-ui.component';
import { MerchantUiComponent } from './merchant-ui/merchant-ui.component';
import { OtpModalUiComponent } from './otp-modal-ui/otp-modal-ui.component';
import { HttpClientModule } from '@angular/common/http';
import { LoadingModalUiComponent } from './loading-modal-ui/loading-modal-ui.component';



@NgModule({
  declarations: [
    AppComponent,
    // DemoComponent,
    CheckoutUiComponent,
    MomoUiComponent,
    CardUiComponent,
    ModalUiComponent,
    MerchantUiComponent,
    OtpModalUiComponent,
    LoadingModalUiComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule
  ],
  providers: [],
  // bootstrap: [AppComponent], // remove this
  bootstrap: [], // use this
  entryComponents: [ CheckoutUiComponent]
})
export class AppModule { 
  constructor(private injector: Injector) {}
  ngDoBootstrap() {
    const el = createCustomElement(CheckoutUiComponent, { injector: this.injector });
    customElements.define('moolre-checkout', el);
  }
}
// ng build --configuration production --output-hashing none