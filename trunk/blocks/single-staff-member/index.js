/**
 * Block dependencies
 */
import './style.scss';
import './editor.scss';

/**
 * Custom components
 */
import PostSelector from '../components/PostSelector';

/**
 * Internal block libraries
 */
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.editor;
const { decodeEntities } = wp.htmlEntities;
const { apiFetch } = wp;
const { ServerSideRender } = wp.components;

const renderAdminStaffMember = props => {
  if ( ! props.attributes.id )
    return <p>Please select a Staff Member.</p>;

  return <ServerSideRender
    block="simple-staff-list/single-staff-member"
    attributes={{
      id: props.attributes.id,
    }}
    />
}

/**
 * Register block
 */
export default registerBlockType(
    'simple-staff-list/single-staff-member',
    {
        title: __( 'Single Staff Member', 'simple-staff-list' ),
        description: __( 'Show a single staff member block.', 'simple-staff-list' ),
        category: 'common',
        icon: 'groups',
        keywords: [
            __( 'Staff member', 'simple-staff-list' ),
            __( 'single', 'simple-staff-list' ),
            __( 'staff list', 'simple-staff-list' ),
        ],
        edit: props => {
          const { className, setAttributes } = props;

          // Fetch the staff data if we have a staff ID but no staffData. This covers the scenario of opening a post with an existing Single Staff Member block.
          if ( props.attributes.id && ! props.attributes.staffData ) {
            apiFetch({
              path: `/wp/v2/staff-member/${props.attributes.id}?_embed`
            }).then(response => {
              const fullpost = {
                title: decodeEntities(response.title.rendered),
                id: response.id,
                excerpt: decodeEntities(response.excerpt.rendered),
                url: response.link,
                date: response.date,
                type: response.type,
                status: response.status,
                media: response._embedded['wp:featuredmedia'][0]
              };
              // send data to the block;
              setAttributes({
                staffData: fullpost
              });
            });
          }

          return (
            <div className={ className }>
              <InspectorControls>
                <PostSelector
                  onPostSelect={post => {
                    setAttributes({
                      staffData: post,
                      id: post.id,
                    });
                  }}
                  inputPlaceholder='Type to search Staff Members'
                  hideInputOnLimit={true}
                  posts={ props.attributes.staffData ? [props.attributes.staffData] : [] }
                  onChange={newValue => {
                    const newStaffData = newValue.length === 0 ? undefined : newValue;
                    setAttributes({
                      staffData: newStaffData,
                      id: newValue.id,
                    });
                  }}
                  postType={'staff-member'}
                  limit="1"
                />
              </InspectorControls>
              <div>
                {
                  renderAdminStaffMember(props)
                }
              </div>
            </div>
          );
        },
        save: () => {
          return null;
        },
    },
);
