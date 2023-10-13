import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MerchantUiComponent } from './merchant-ui.component';

describe('MerchantUiComponent', () => {
  let component: MerchantUiComponent;
  let fixture: ComponentFixture<MerchantUiComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ MerchantUiComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(MerchantUiComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
