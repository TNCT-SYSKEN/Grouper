package org.sysken.grouper;

import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.Color;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.support.v4.app.LoaderManager.LoaderCallbacks;
import android.support.v4.content.AsyncTaskLoader;
import android.support.v4.content.Loader;
import android.view.View;
import android.view.View.OnClickListener;
import android.webkit.WebView;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;

import com.google.zxing.BarcodeFormat;
import com.google.zxing.common.BitMatrix;
import com.google.zxing.qrcode.QRCodeWriter;

public class GenerateActivity extends FragmentActivity {


    public String number;
    @Override
    protected void onCreate(Bundle savedInstanceState) {

        Intent intent = getIntent();
        // intentから指定キーの文字列を取得する
        number = intent.getStringExtra( "number" );
        super.onCreate(savedInstanceState);
        setContentView(R.layout.generate);

        // 非同期でエンコードする
        Bundle bundle = new Bundle();
        bundle.putString("contents", number);
        getSupportLoaderManager().initLoader(0, bundle, callbacks);
    }


    private LoaderCallbacks<Bitmap> callbacks = new LoaderCallbacks<Bitmap>() {
        @Override
        public Loader<Bitmap> onCreateLoader(int id, Bundle bundle) {
            EncodeTaskLoader loader = new EncodeTaskLoader(
                    getApplicationContext(), bundle.getString("contents"));
            loader.forceLoad();
            return loader;
        }
        @Override
        public void onLoaderReset(Loader<Bitmap> loader) {
        }
        @Override
        public void onLoadFinished(Loader<Bitmap> loader, Bitmap bitmap) {
            getSupportLoaderManager().destroyLoader(0);
            if (bitmap == null) {
                // エンコード失敗
                Toast.makeText(getApplicationContext(), "Error.", Toast.LENGTH_SHORT).show();
            } else {
                // エンコード成功
                ImageView imageView = (ImageView) findViewById(R.id.result_view);
                imageView.setImageBitmap(bitmap);
            }
        }
    };

    public static class EncodeTaskLoader extends AsyncTaskLoader<Bitmap> {
        private String mContents;
        public EncodeTaskLoader(Context context, String contents) {
            super(context);
            mContents = contents;
        }
        @Override
        public Bitmap loadInBackground() {
            try {
                // エンコード結果を返す
                return encode(mContents);
            } catch (Exception e) {
                // 何らかのエラーが発生したとき
                return null;
            }
        }
        private Bitmap encode(String contents) throws Exception {
            QRCodeWriter writer = new QRCodeWriter();
            // エンコード
            BitMatrix bm = null;
            bm = writer.encode(mContents, BarcodeFormat.QR_CODE, 300, 300);
            // ピクセルを作る
            int width = bm.getWidth();
            int height = bm.getHeight();
            int[] pixels = new int[width * height];
            // データがあるところだけ黒にする
            for (int y = 0; y < height; y++) {
                int offset = y * width;
                for (int x = 0; x < width; x++) {
                    pixels[offset + x] = bm.get(x, y) ? Color.BLACK : Color.WHITE;
                }
            }
            Bitmap bitmap = Bitmap.createBitmap(width, height, Bitmap.Config.ARGB_8888);
            bitmap.setPixels(pixels, 0, width, 0, 0, width, height);
            return bitmap;
        }
    }
}