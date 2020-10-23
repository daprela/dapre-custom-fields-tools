const { __ } = wp.i18n;

const OptionsMetaHeaders = ({ className }) => (
  <div className={className}>
    <div>
      Option Name
    </div>
    <div>
      {__('Actions', 'dapre-cft')}
    </div>
    <div>
      {__('Value to Add', 'dapre-cft')}
    </div>
    <div>
      {__('Current Value', 'dapre-cft')}
    </div>
    <div>
      {__('Current Value Options', 'dapre-cft')}
    </div>
    <div>
      {__('Previous Value', 'dapre-cft')}
    </div>
  </div>
);

export default OptionsMetaHeaders;
