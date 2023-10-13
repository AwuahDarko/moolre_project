import { ComponentFixture, TestBed } from '@angular/core/testing';

import { OtpModalUiComponent } from './otp-modal-ui.component';

describe('OtpModalUiComponent', () => {
  let component: OtpModalUiComponent;
  let fixture: ComponentFixture<OtpModalUiComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ OtpModalUiComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(OtpModalUiComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
