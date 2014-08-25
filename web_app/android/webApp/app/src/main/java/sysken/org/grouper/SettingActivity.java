package sysken.org.grouper;

import android.app.Activity;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.EditText;
import android.widget.TextView;


public class SettingActivity extends Activity  {

    // ������\������TextView
    TextView textView1;
    // �������͂���EditText
    EditText editText1;

    Globals globals;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_setting);


        globals = (Globals) this.getApplication();

        {
            textView1 = (TextView) findViewById(R.id.textView2);
            editText1 = (EditText) findViewById(R.id.editText1);

            findViewById(R.id.button1).setOnClickListener(new OnClickListener() {
                @Override
                public void onClick(View v) {
                    // ��͂��ꂽ������\���p��TextView�ɐݒ肷��
                    StringBuilder buf = new StringBuilder();

                    buf.append(editText1.getText().toString());

                    textView1.setText(buf.toString());
                    globals.url_string = "http://" + buf.toString();
                }
            });
        }

        findViewById(R.id.button3).setOnClickListener(new OnClickListener() {
            @Override
            public void onClick(View v) {
                globals.web.loadUrl(globals.url_string);
                finish();
            }
        });

    }

}
