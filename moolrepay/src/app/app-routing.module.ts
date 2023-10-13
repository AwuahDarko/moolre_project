import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CheckoutUiComponent } from './checkout-ui/checkout-ui.component';
import { MerchantUiComponent } from './merchant-ui/merchant-ui.component';


const routes: Routes = [
  { path: '', redirectTo: '/', pathMatch: 'full' },
  {
    path: '',
    component: CheckoutUiComponent
  },
  {
    path: 'merchant',
    component: MerchantUiComponent
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
